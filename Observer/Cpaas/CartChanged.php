<?php

namespace Dotdigitalgroup\Email\Observer\Cpaas;

/**
 * Process changes to the current cart.
 */
class CartChanged implements \Magento\Framework\Event\ObserverInterface
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
     *  @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Dotdigitalgroup\Email\Helper\Cpaas $cpaas_helper,
        \Psr\Log\LoggerInterface $logger
    ) {
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
        try {            
            $cart = $observer->getData("cart");
            $cartId = $cart->getQuote()->getId();

            $this->_logger->info("Shopping cart updated (Cart Quote Id: " . $cartId . ") for " . (string)$this->_cpaas_helper->getProfileId());

            if ($cartId != $this->_cpaas_helper->getSavedCartId())
            {
                // Cart id has changed, sync it
                $this->_logger->info("Updating cart quote id on CPaaS platform...");
                $this->_cpaas_helper->updateProfile($this->_cpaas_helper->getProfileId(), [ "cartQuoteId" => $cartId ]);
                $this->_cpaas_helper->setSavedCartId($cartId);
            }

            return $this;
        } catch (Exception $ex) {
            $this->_logger->error($ex->getMessage());
        }
    }
}
