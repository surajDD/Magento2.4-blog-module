<?php
namespace Mastering\BlogManager\Model;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;


use Mastering\BlogManager\Api\Data\blogInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;


class Blog extends AbstractModel  implements IdentityInterface,blogInterface
{
    const NOROUTE_ENTITY_ID = 'no-route';
  
    const CACHE_TAG = 'Mastering_blogmanager_blog';
    protected $_cacheTag = 'Mastering_blogmanager_blog';
    protected $_eventPrefix = 'Mastering_blogmanager_blog';
    
    public function _construct()
    {
        $this->_init(\Mastering\BlogManager\Model\ResourceModel\Blog::class);
    }
    
    public function load($id, $field = null)
    {
        if ($id === null) {
            return $this->noRoute();
        }
        return parent::load($id, $field);
    }
    
    public function noRoute()
    {
        return $this->load(self::NOROUTE_ENTITY_ID, $this->getIdFieldName());
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG.'_'.$this->getId()];
    }

    public function getId()
    {
        return parent::getData(self::ENTITY_ID);
    }

    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }
	
	public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }
	
	public function getStatus()
    {
        return $this->getData(self::STATUS);
    }
	
	public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }
	
	public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }
	

    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }
	
	public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }
	
	public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }
	
	public function setCreatedAt($created_at)
    {
        return $this->setData(self::CREATED_AT, $created_at);
    }
	
	public function setUpdatedAt($updated_at)
    {
        return $this->setData(self::UPDATED_AT, $updated_at);
    }

}
