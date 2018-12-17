<?php

namespace Dotdigitalgroup\Email\Controller\Adminhtml\Communications;

class Index extends \Magento\Backend\App\AbstractAction
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Dotdigitalgroup_Email::communications_settings';

    /**
     * Execute method.
     */
    public function execute()
    {
        //Redirect to developer section config
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('adminhtml/system_config/edit', ['section' => 'ddg_cpaas_settings']);

        return $resultRedirect;
    }
}
