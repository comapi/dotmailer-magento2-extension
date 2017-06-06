<?php

namespace Dotdigitalgroup\Email\Observer\Adminhtml;

/**
 * Reset the contact import after changing the mapping.
 */
class ResetContactImport implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Dotdigitalgroup\Email\Helper\Data
     */
    private $helper;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    private $messageManager;
    /**
     * @var \Dotdigitalgroup\Email\Model\ContactFactory
     */
    private $contactFactory;
    /**
     * @var \Dotdigitalgroup\Email\Model\ResourceModel\ContactFactory
     */
    private $contactResourceFactory;

    /**
     * ResetContactImport constructor.
     *
     * @param \Dotdigitalgroup\Email\Model\ResourceModel\ContactFactory $contactResourceFactory
     * @param \Dotdigitalgroup\Email\Model\ContactFactory               $contactFactory
     * @param \Dotdigitalgroup\Email\Helper\Data                        $data
     * @param \Magento\Backend\App\Action\Context                       $context
     */
    public function __construct(
        \Dotdigitalgroup\Email\Model\ResourceModel\ContactFactory $contactResourceFactory,
        \Dotdigitalgroup\Email\Model\ContactFactory $contactFactory,
        \Dotdigitalgroup\Email\Helper\Data $data,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->contactFactory         = $contactFactory;
        $this->contactResourceFactory = $contactResourceFactory;
        $this->helper                 = $data;
        $this->messageManager         = $context->getMessageManager();
    }

    /**
     * Execute method.
     *
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $contactModel = $this->contactResourceFactory->create();
        $numImported = $this->contactFactory->create()
            ->getNumberOfImportedContacs();

        $updated = $contactModel->resetAllContacts();

        $this->helper->log('-- Imported contacts: ' . $numImported
            . ' reseted :  ' . $updated . ' --');

        return $this;
    }
}
