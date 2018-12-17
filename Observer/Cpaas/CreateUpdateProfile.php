<?php

namespace Dotdigitalgroup\Email\Observer\Cpaas;

/**
 * Register new customer automation.
 */
class CreateUpdateProfile implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Dotdigitalgroup\Email\Helper\Cpaas
     */
    private $_cpaas_helper;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * Constructor.
     * 
     *  @param \Dotdigitalgroup\Email\Helper\Cpaas $cpaas_helper
     *
     */
    public function __construct(
        \Dotdigitalgroup\Email\Helper\Cpaas $cpaas_helper,
        \Psr\Log\LoggerInterface $logger)
    {
        $this->_cpaas_helper = $cpaas_helper;
        $this->_logger = $logger;
    }

    /**
     * Create or update the profile related to the logged in Magento user
     *
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();
        $this->_logger->info("User logged in: " . $customer->getName());
        
        return $this;
    }
}
