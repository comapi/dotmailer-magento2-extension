<?php
namespace Dotdigitalgroup\Email\Helper;

use \Magento\Framework\App\Config\ScopeConfigInterface as scopeConfig;

/**
 * General most used helper to work with config data, saving updating and generating for dotdigital CPaaS functionality
 */
class Cpaas extends \Magento\Framework\App\Helper\AbstractHelper
{

    const SETTINGS_PATH = 'ddg_cpaas_settings/settings/';
    const JWT_SETTINGS_PATH = 'ddg_cpaas_settings/jwt/';

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * @var \Magento\Framework\App\Helper\Context
     */
    protected $_context;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlInterface;

    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $_cart;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectMgr;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\UrlInterface $urlInterface
     * @param \Magento\Checkout\Model\Cart $cart
     * @param \Magento\Framework\ObjectManagerInterface $objectMgr
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Framework\ObjectManagerInterface $objectMgr
    ) {
        $this->_logger = $logger;
        $this->_context = $context;
        $this->_customerSession = $customerSession;
        $this->_urlInterface = $urlInterface;
        $this->_cart = $cart;
        $this->_objectMgr = $objectMgr;

        if (is_null($customerSession)) {
            $this->_objectMgr = $this->getCustomerSession();
        } else {
            $this->_customerSession->start();
        }

        parent::__construct($context);
    }

    /*
     * ------------------- Private --------------------
     */

    /**
     * Get config scope value.
     *
     * @param string $path
     * @param string $contextScope
     * @param null $contextScopeId
     *
     * @return int|float|string|boolean
     */
    private function getConfigValue(
        $path,
        $contextScope = 'default',
        $contextScopeId = null
    ) {
        $config = $this->scopeConfig->getValue(
            $path,
            $contextScope,
            $contextScopeId
        );

        return $config;
    }

    /**
     * @param string $fullName
     * 
     * @return array
     */
    private function getNameParts($fullName)
    {
        $parts = explode(" ", $fullName);

        if (count($parts) < 2) {
            if (count($parts) == 0) {
                return [
                    "firstName" => "",
                    "lastName" => ""
                ];
            } else {
                return [
                    "firstName" => $parts[0],
                    "lastName" => ""
                ];
            }
        } else {
            return [
                "firstName" => $parts[0],
                "lastName" => $parts[1]
            ];
        }
    }

    /**
     * @param object $variable
     * @param string $message
     */
    private function logVariable($variable, $message = "Variable dump: ")
    {
        ob_start();
        var_dump($variable);
        $this->_logger->addInfo($message . ob_get_clean());
    }


    /*
     * ------------------- Public --------------------
     */

    /**
     * Get a the customers Magento session
     * 
     * @return \Magento\Customer\Model\Session
     */
    public function getCustomerSession()
    {
        return $this->_objectMgr->create('Magento\Customer\Model\SessionFactory')->create();
    }

    /**
     * Gets the CPaaS profile id for the customer.
     * @return string
     */
    public function getProfileId()
    {
        return $this->_customerSession->getProfileId();
    }

    /**
     * Sets the CPaaS profile id for the customer.
     * 
     * @param string $profileId
     */
    public function setProfileId($profileId)
    {
        $this->_customerSession->setProfileId($profileId);
    }

    /**
     * Gets the cart id that has been synched to the CPaaS profile
     * @return string
     */
    public function getSavedCartId()
    {
        return $this->_customerSession->getSavedCartId();
    }

    /**
     * Sets the cart id that has been synched to the CPaaS profile
     * 
     * @param string $cartId
     */
    public function setSavedCartId($cartId)
    {
        $this->_customerSession->setSavedCartId($cartId);
    }

    /**
     * Gets the customer Magento id
     *
     * @return string
     */
    public function getCustomerId()
    {
        return (string)$this->_customerSession->getCustomerId();
    }

    /**
     * Gets the customer cart quote id
     *
     * @return string
     */
    public function getCartQuoteId()
    {
        return (string)$this->_cart->getQuote()->getId();
    }

    /**
     * Gets the logged in users details.
     * 
     * @return array
     */
    public function getCustomerDetails()
    {
        if ($this->_customerSession->isLoggedIn()) {
            $details = [
                "magentoId" => (string)$this->_customerSession->getCustomer()->getId(),  // get Customer Id
                "name" => (string)$this->_customerSession->getCustomer()->getName(),  // get  Full Name
                "email" => (string)$this->_customerSession->getCustomer()->getEmail(), // get Email Name
                "groupId" => (string)$this->_customerSession->getCustomer()->getGroupId(),  // get Customer Group Id
                "storeUrl" => (string)$this->_urlInterface->getBaseUrl(), // The Magento stores url
                "storeId" => (string)$this->_customerSession->getCustomer()->getStoreId(), // The Magento store id
                "cartQuoteId" => $this->getCartQuoteId(), // Magento cart quote id
                "savedCartQuoteId" => $this->getSavedCartId(), // Synched Magento cart id
                "profileId" => $this->getProfileId() // CPaaS profile Id
            ];

            return $details;
        }
        else
        {
            return NULL;
        }
    }

    /**
     * API Space Id datafield.
     *
     * @return string
     */
    public function getApiSpaceId()
    {
        return $this->getConfigValue(
            Cpaas::SETTINGS_PATH . 'apispace_id',
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * API Space token datafield.
     *
     * @return string
     */
    public function getApiSpaceToken()
    {
        return $this->getConfigValue(
            Cpaas::SETTINGS_PATH . 'apispace_token',
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * Update the CPaaS profile with Magento customer details
     * @param String $profileId
     *
     * @return boolean
     */
    public function syncCustomerToProfile($profileId)
    {
        // Get latest customer data
        $customerData = $this->getCustomerDetails();

        $data = [
            "Magento" => [
                "magentoId" => $customerData["magentoId"],
                "groupId" => $customerData["groupId"],
                "storeUrl" => $customerData["storeUrl"],
                "storeId" => $customerData["storeId"],
                "cartQuoteId" => $customerData["cartQuoteId"]
            ]
        ];
        
        // Store the parameters in the customers session
        $this->setProfileId($profileId);
        $this->setSavedCartId($customerData["cartQuoteId"]);

        // Add user level details
        $firstName = $this->getNameParts($customerData["name"])["firstName"];
        $lastName = $this->getNameParts($customerData["name"])["lastName"];

        if ($firstName != "") {
            $data["firstName"] = $firstName;
        }

        if ($lastName != "") {
            $data["lastName"] = $lastName;
        }

        if ($customerData["email"] != "") {
            $data["email"] = $customerData["email"];
        }

        // Perform the update
        if (!$this->updateProfile($profileId, $data)) {
            $this->_logger->addError("Failed to sync user details to profile id: " . $profileId);
            return false;
        } else {
            $this->_logger->addInfo("Synched user details to CPaaS profile: " . $profileId);
            return true;
        }
    }

    /**
     * Update the CPaaS profile
     * @param String $profileId
     *
     * @return boolean
     */
    public function updateProfile($profileId, $data)
    {
        // Get latest customer data
        $curl = curl_init();
        $this->_logger->addInfo("Updating CPaaS profile:" . $profileId);
        $this->_logger->addInfo("Data: " . json_encode($data));

        // Capture curl output
        ob_start();
        $out = fopen('php://output', 'w');

        curl_setopt_array($curl, array(
            CURLOPT_VERBOSE => true,
            CURLOPT_STDERR => $out,
            CURLOPT_URL => "https://api.comapi.com/apispaces/" . $this->getApiSpaceId() . "/profiles/" . urlencode($profileId),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PATCH",
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "content-type: application/json",
                "authorization: Bearer " . $this->getApiSpaceToken()
            ),
            CURLOPT_POSTFIELDS => json_encode($data)
        ));

        $response = curl_exec($curl);
        fclose($out);
        $debug = ob_get_clean();
        $this->_logger->addInfo("Curl debug: " . $debug);

        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $this->_logger->addError("Error updating profile, Curl Error #:" . json_encode($err));
            return false;
        } else {
            $this->_logger->addInfo("Updated profile " . $profileId . " Response was: " . $response);
            return true;
        }
    }

}
