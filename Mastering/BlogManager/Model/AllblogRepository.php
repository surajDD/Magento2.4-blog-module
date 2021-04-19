<?php

namespace Mastering\BlogManager\Model;

use Mastering\BlogManager\Api\Data;
use Mastering\BlogManager\Api\AllblogRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Mastering\BlogManager\Model\ResourceModel\blog as ResourceAllblog;
use Mastering\BlogManager\Model\ResourceModel\blog\CollectionFactory as AllblogCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class AllblogRepository implements AllblogRepositoryInterface
{
    protected $resource;

    protected $blogFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataAllblogFactory;

    private $storeManager;

    public function __construct(
        ResourceAllblog $resource,
        blogFactory $blogFactory,
        Data\BlogInterfaceFactory $datablogFactory,
        DataObjectHelper $dataObjectHelper,
		DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
		$this->blogFactory = $blogFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->datablogFactory = $datablogFactory;
		$this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    public function save(\Mastering\BlogManager\Api\Data\blogInterface $blog)
    {
        if ($blog->getStoreId() === null) {
            $storeId = $this->storeManager->getStore()->getId();
            $blog->setStoreId($storeId);
        }
        try {
            $this->resource->save($blog);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the blog: %1', $exception->getMessage()),
                $exception
            );
        }
        return $blog;
    }

    public function getById($entityId)
    {
		$blog = $this->blogFactory->create();
        $blog->load($entityId);
        if (!$blog->getId()) {
            throw new NoSuchEntityException(__('blog with id "%1" does not exist.', $entityId));
        }
        return $blog;
    }
	
    public function delete(\Mastering\BlogManager\Api\Data\blogInterface $blog)
    {
        try {
            $this->resource->delete($blog);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the blog: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    public function deleteById($entityId)
    {
        return $this->delete($this->getById($entityId));
    }
}
