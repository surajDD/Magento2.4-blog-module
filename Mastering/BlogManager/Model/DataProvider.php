<?php
/**
 * Mastering Software.
 *
 * @category  Mastering
 * @package   Mastering_UiForm
 * @author    Mastering
 * @copyright Copyright (c) 2010-2016 Mastering Software Private Limited (https://Mastering.com)
 * @license   https://store.Mastering.com/license.html
 */
namespace Mastering\BlogManager\Model;
 
use Mastering\BlogManager\Model\ResourceModel\blog\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
 
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \Rsgitech\News\Model\ResourceModel\Allnews\Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;
    /**
     * @var array
     */
    protected $_loadedData;


     /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $blogCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        DataPersistorInterface $dataPersistor,
        CollectionFactory $blogCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $blogCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->meta = $this->prepareMeta($this->meta);
    }
  /**
     * Prepares Meta
     *
     * @param array $meta
     * @return array
     */
    public function prepareMeta(array $meta)
    {
        return $meta;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }
        $items = $this->collection->getItems();
           /** @var $blog \Mastering\BlogManager\Model\blog */
        foreach ($items as $blog) {
            $this->_loadedData[$blog->getId()] = $blog->getData();
        }
        $data = $this->dataPersistor->get('blog_allblog');
        if (!empty($data)) {
            $blog = $this->collection->getNewEmptyItem();
            $blog->setData($data);
            $this->_loadedData[$blog->getId()] = $blog->getData();
            $this->dataPersistor->clear('blog_allblog');
        }
        return $this->_loadedData;
    }
}