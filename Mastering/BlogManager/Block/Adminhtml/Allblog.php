<?php
namespace Mastering\BlogManager\Block\Adminhtml;

class Allblog extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_controller = 'adminhtml_allnews';
        $this->_blockGroup = 'Mastering_BlogManager';
        $this->_headerText = __('Manage blog');

        parent::_construct();

        if ($this->_isAllowedAction('Mastering_BlogManager::save')) {
            $this->buttonList->update('add', 'label', __('Add blog'));
        } else {
            $this->buttonList->remove('add');
        }
    }

    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
