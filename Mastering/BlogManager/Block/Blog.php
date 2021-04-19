<?php
namespace Mastering\BlogManager\Block;

class Blog extends \Magento\Framework\View\Element\Template
{
    public $blogFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Mastering\BlogManager\Model\BlogFactory $blogFactory,
        array $data = []
    ) {
        $this->blogFactory = $blogFactory;
        parent::__construct($context, $data);
    }

    public function getBlog()
    {
        $blogId = $this->getRequest()->getParam('id');
        return $this->blogFactory->create()->load($blogId);
    }
}