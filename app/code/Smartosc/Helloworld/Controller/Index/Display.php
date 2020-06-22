<?php

namespace Smartosc\Helloworld\Controller\Index;

class Display extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory)
    {
        $this->_pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    public function execute()
    {
        $page = $this->_pageFactory->create();
        $username = $this->getRequest()->getParam('username');
        $block = $page->getLayout()->getBlock('helloworld_display');
        $block->setData('username', $username );
        return $page;
    }
}




