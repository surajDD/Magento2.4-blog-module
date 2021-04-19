<?php
namespace Mastering\BlogManager\Block;


class BlogList extends \Magento\Framework\View\Element\Template
{
    public $blogCollection;
    public $customerSession;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Mastering\BlogManager\Model\ResourceModel\Blog\CollectionFactory $blogCollection,
        \Magento\Customer\Model\SessionFactory $customerSession,
        array $data = []
    ) {
        $this->blogCollection = $blogCollection;
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    public function getBlogs()
    {
        $customer = $this->customerSession->create();
        $customerId = $customer->getCustomer()->getId();

        $collection = $this->blogCollection->create();
        $collection->addFieldToFilter('user_id',['eq'=>$customerId])
                   ->setOrder('updated_at', 'DESC');

        return $collection;
    }
}