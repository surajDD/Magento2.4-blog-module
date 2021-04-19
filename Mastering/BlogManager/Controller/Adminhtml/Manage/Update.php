<?php
namespace Mastering\BlogManager\Controller\Adminhtml\Manage;

use Magento\Customer\Controller\AbstractAccount;
use Magento\Framework\App\Action\Context;


class Update extends AbstractAccount
{
    public $blogFactory;
  
    public $messageManager;

    public function __construct(
        Context $context,
        \Mastering\BlogManager\Model\BlogFactory $blogFactory,
      
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->blogFactory = $blogFactory;
      
        $this->messageManager = $messageManager;
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getParams();
        var_dump($data);exit;
        if (isset($data['id']) && $data['id']) {
            $isAuthorised = $this->blogFactory->create()
                                        ->getCollection()
                                        ->addFieldToFilter('entity_id', $data['id'])
                                        ->getSize();
            if (!$isAuthorised) {
                $this->messageManager->addError(__('You are not authorised to edit this blog.'));
                return $this->resultRedirectFactory->create()->setPath('blog/manage');
            } else {
                $model = $this->blogFactory->create()->load($data['id']);
                $model->setTitle($data['title'])
                    ->setContent($data['content'])
                    ->save();
                $this->messageManager->addSuccess(__('You have updated the blog successfully.'));
            }
        } 
        return $this->resultRedirectFactory->create()->setPath('blog/manage');
    }
}