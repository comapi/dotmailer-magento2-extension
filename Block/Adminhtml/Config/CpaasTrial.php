<?php

namespace Dotdigitalgroup\Email\Block\Adminhtml\Config;

class CpaasTrial extends \Magento\Config\Block\System\Config\Form\Fieldset
{
    /**
     * @var \Dotdigitalgroup\Email\Helper\Data
     */
    public $helper;

    /**
     * Trial constructor.
     *
     * @param \Magento\Backend\Block\Context $context
     * @param \Magento\Backend\Model\Auth\Session $authSession
     * @param \Magento\Framework\View\Helper\Js $jsHelper
     * @param \Dotdigitalgroup\Email\Helper\Data $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\View\Helper\Js $jsHelper,
        \Dotdigitalgroup\Email\Helper\Data $helper,
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct($context, $authSession, $jsHelper, $data);
    }

    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     *
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $html = '<img src="' . $this->getViewFileUrl('Dotdigitalgroup_Email::images/ddec_logo.png') . '" style="width: 500px;" />' .
            '<p>Want to try our great CPaaS features for free to transform your customers experiences, then <a href="https://portal.comapi.com/#/register" target="_blank"><b>click here for a free trial account</b></a>.
            <br/>
            If you already have a dotdigital CPaaS account, enter your API Space details below.
            </p>';

        return $html;
    }
}