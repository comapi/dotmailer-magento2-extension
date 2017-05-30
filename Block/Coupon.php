<?php

namespace Dotdigitalgroup\Email\Block;

use Dotdigitalgroup\Email\Helper\Config;

class Coupon extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Dotdigitalgroup\Email\Helper\Data
     */
    public $helper;
    /**
     * @var \Dotdigitalgroup\Email\Model\ResourceModel\CampaignFactory
     */
    private $campaignFactory;

    /**
     * Coupon constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context              $context
     * @param \Dotdigitalgroup\Email\Helper\Data                            $helper
     * @param \Dotdigitalgroup\Email\Model\ResourceModel\CampaignFactory    $campaignFactory
     * @param array                                                         $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Dotdigitalgroup\Email\Helper\Data $helper,
        \Dotdigitalgroup\Email\Model\ResourceModel\CampaignFactory $campaignFactory,
        array $data = []
    ) {
        $this->helper = $helper;
        $this->campaignFactory = $campaignFactory;
        parent::__construct($context, $data);
    }

    /**
     * Generates the coupon code based on the code id.
     *
     * @return bool
     */
    public function generateCoupon()
    {
        $params = $this->getRequest()->getParams();
        //check for param code and id
        if (!isset($params['id']) || !isset($params['code'])) {
            $this->helper->log('Coupon no id or code is set');

            return false;
        }

        $couponCodeId = $params['id'];
        $expireDate = false;

        if (is_numeric($params['expire_days'])) {
            $expireDate = $this->_localeDate->date()
                ->add(\DateInterval::createFromDateString(sprintf('P%sDay', $params['expire_days'])));
        }

        return $this->campaignFactory->create()
            ->generateCoupon($couponCodeId, $expireDate);
    }

    /**
     * @return array
     */
    public function getStyle()
    {
        return explode(
            ',',
            $this->helper->getWebsiteConfig(Config::XML_PATH_CONNECTOR_DYNAMIC_COUPON_STYLE)
        );
    }

    /**
     * Coupon color from config.
     *
     * @return mixed
     */
    public function getCouponColor()
    {
        return $this->helper->getWebsiteConfig(
            Config::XML_PATH_CONNECTOR_DYNAMIC_COUPON_COLOR
        );
    }

    /**
     * Coupon font size from config.
     *
     * @return mixed
     */
    public function getFontSize()
    {
        return $this->helper->getWebsiteConfig(
            Config::XML_PATH_CONNECTOR_DYNAMIC_COUPON_FONT_SIZE
        );
    }

    /**
     * Coupon Font from config.
     *
     * @return mixed
     */
    public function getFont()
    {
        return $this->helper->getWebsiteConfig(
            Config::XML_PATH_CONNECTOR_DYNAMIC_COUPON_FONT
        );
    }

    /**
     * Coupon background color from config.
     *
     * @return mixed
     */
    public function getBackgroundColor()
    {
        return $this->helper->getWebsiteConfig(
            Config::XML_PATH_CONNECTOR_DYNAMIC_COUPON_BG_COLOR
        );
    }
}
