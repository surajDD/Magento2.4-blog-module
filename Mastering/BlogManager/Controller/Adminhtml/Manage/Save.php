<?php

namespace Mastering\BlogManager\Controller\Adminhtml\Manage;

use Magento\Backend\App\Action;
use Mastering\BlogManager\Model\blog;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var \Mastering\BlogManager\Model\blogFactory
     */
    private $blogFactory;

    /**
     * @var \Mastering\BlogManager\Api\AllblogRepositoryInterface
     */
    private $allblogRepository;

    /**
     * @param Action\Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param \Mastering\BlogManager\Model\blogFactory $blogFactory
     * @param \Mastering\BlogManager\Api\AllblogRepositoryInterface $allblogRepository
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        \Mastering\BlogManager\Model\blogFactory $blogFactory = null,
        \Mastering\BlogManager\Api\AllblogRepositoryInterface $allblogRepository = null
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->blogFactory = $blogFactory
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(\Mastering\BlogManager\Model\blogFactory::class);
        $this->allblogRepository = $allblogRepository
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(\Mastering\BlogManager\Api\AllblogRepositoryInterface::class);
        parent::__construct($context);
    }
	
	/**
     * Authorization level
     *
     * @see _isAllowed()
     */
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('Mastering_BlogManager::save');
	}

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (isset($data['status']) && $data['status'] === 'true') {
                $data['status'] = blog::STATUS_ENABLED;
            }
            if (empty($data['entity_id'])) {
                $data['entity_id'] = null;
            }

            /** @var \Mastering\BlogManager\Model\blog $model */
            $model = $this->blogFactory->create();

            $id = $this->getRequest()->getParam('entity_id');
            if ($id) {
                try {
                    $model = $this->allblogRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This blog no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

            $this->_eventManager->dispatch(
                'blog_allblog_prepare_save',
                ['blog' => $model, 'request' => $this->getRequest()]
            );

            try {
                $this->allblogRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the blog.'));
                $this->dataPersistor->clear('blog_allblog');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['entity_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addExceptionMessage($e->getPrevious() ?:$e);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the blog.'));
            }

            $this->dataPersistor->set('blog_allblog', $data);
            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $this->getRequest()->getParam('entity_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
