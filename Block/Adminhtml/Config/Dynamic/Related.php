<?php
namespace Dotdigitalgroup\Email\Block\Adminhtml\Config\Dynamic;

class Related extends \Magento\Config\Block\System\Config\Form\Field
{

	public function __construct(
		\Dotdigitalgroup\Email\Helper\Data $dataHelper,
		\Magento\Backend\Block\Template\Context $context
	)
	{
		$this->_dataHelper = $dataHelper;

		parent::__construct($context);
	}

	protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
	    //passcode to append for url
	    $passcode = $this->_dataHelper->getPasscode();
	    //last order id witch information will be generated
	    $lastOrderId = $this->_dataHelper->getLastOrderId();

	    if(!strlen($passcode))
		    $passcode = '[PLEASE SET UP A PASSCODE]';
	    if(!$lastOrderId)
		    $lastOrderId = '[PLEASE MAP THE LAST ORDER ID]';

	    //generate the base url and display for default store id
	    $baseUrl = $this->_dataHelper->generateDynamicUrl();

	    //display the full url
        $text = sprintf('%sconnector/products/related/code/%s/order_id/@%s@', $baseUrl, $passcode, $lastOrderId);
        $element->setData('value', $text);

        return parent::_getElementHtml($element);
    }
}