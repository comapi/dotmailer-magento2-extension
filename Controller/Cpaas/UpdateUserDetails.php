<?php

namespace Dotdigitalgroup\Email\Controller\Cpaas;

class UpdateUserDetails extends \Magento\Framework\App\Action\Action
{
    const COMAPI_PROFILE_KEY = "comapiProfileId";

    /**
     * @var \Dotdigitalgroup\Email\Helper\Cpaas
     */
    private $_helper;

    private $_resultResultFactory;

    /**
     * UpdateUserDetails constructor.
     * @param \Dotdigitalgroup\Email\Helper\Cpaas $helper
     * @param \Magento\Framework\View\Result $resultResultFactory
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Dotdigitalgroup\Email\Helper\Cpaas $helper,
        \Magento\Framework\App\Action\Context $context
    ) {
        $this->_helper = $helper;
        $this->_resultResultFactory = $context->getResultFactory();

        parent::__construct($context);
    }

    /**
     * Easy email capture for Newsletter and Checkout.
     *
     * @return null
     */
    public function execute()
    {
        try {
            $postData = $this->getRequest()->getPostValue();
            $result = $this->_resultResultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_RAW);

            if (array_key_exists(self::COMAPI_PROFILE_KEY, $postData)) {
                // All ok, set magento user details in the chat profile
                $comapiProfile = (String)$postData[self::COMAPI_PROFILE_KEY];
                
                $this->_helper->syncCustomerToProfile($comapiProfile);

                return $result
                    ->setHeader("Status", "200 OK")
                    ->setContents("User details updated for Magento user: " . $comapiProfile);
            } else {
                // No Comapi profile id found!
                return $result
                    ->setHttpResponseCode(404)
                    ->setHeader("Status", "404 File not found")
                    ->setContents("The form field comapiProfileId was not found!");
            }
        } catch (Exception $ex) {
            return $result
                ->setHttpResponseCode(500)
                ->setHeader("Status", "500 Internal error")
                ->setContents("An error occurred: " . $ex->getMessage());
        }
    }
}
