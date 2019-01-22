<?php
namespace Dotdigitalgroup\Email\Controller\Adminhtml\System\Config;

use \Magento\Catalog\Model\Product\Visibility;

class Button extends \Magento\Backend\App\Action
{
    protected $_logger;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->_logger = $logger;
        parent::__construct($context);
    }
    public function execute()
    {
        $this->_logger->debug('button pressed!!');
        // Code to perform specific action    
    }
}