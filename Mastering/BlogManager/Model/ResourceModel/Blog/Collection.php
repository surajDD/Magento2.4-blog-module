<?php
namespace Mastering\BlogManager\Model\ResourceModel\Blog;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';

    protected $_eventPrefix = 'blog_allblog_collection';

    protected $_eventObject = 'allblog_collection';
    
    public function _construct()
    {
        $this->_init(
            \Mastering\BlogManager\Model\Blog::class,
            \Mastering\BlogManager\Model\ResourceModel\Blog::class
        );
        $this->_map['fields']['entity_id'] = 'main_table.entity_id';
    }
}
