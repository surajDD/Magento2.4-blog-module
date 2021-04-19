<?php
namespace Mastering\BlogManager\Controller\Manage;

use Magento\Customer\Controller\AbstractAccount;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;

class Delete extends AbstractAccount
{
    public $blogFactory;
    public $customerSession;
    public $messageManager;

    public function __construct(
        Context $context,
        \Mastering\BlogManager\Model\BlogFactory $blogFactory,
        Session $customerSession,
        \Magento\Framework\Json\Helper\Data $jsonData
    ) {
        $this->blogFactory = $blogFactory;
        $this->customerSession = $customerSession;
        $this->jsonData = $jsonData;
        parent::__construct($context);
    }

    public function execute()
    {
        $blogId = $this->getRequest()->getParam('id');
        $customerId = $this->customerSession->getCustomerId();
        $isAuthorised = $this->blogFactory->create()
                                    ->getCollection()
                                    ->addFieldToFilter('user_id', $customerId)
                                    ->addFieldToFilter('entity_id', $blogId)
                                    ->getSize();
        if (!$isAuthorised) {
            $msg=__('You are not authorised to delete this blog.');
            $success=0;
        } else {
            $model = $this->blogFactory->create()->load($blogId);
            $model->delete();
            $msg=__('You have successfully deleted the blog.');
            $success=1;
        }     
        $this->getResponse()->setHeader('Content-type', 'application/javascript');
        $this->getResponse()->setBody(
            $this->jsonData->jsonEncode(
                    [
                        'success' => $success,
                        'message' => $msg
                    ]
                ));
    }
}