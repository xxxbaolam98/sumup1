<?php

namespace Smartosc\Brand\Controller\Adminhtml\Brand;

use Magento\Framework\View\Result\PageFactory;

class BrandList extends \Magento\Backend\App\Action
{
    protected $resultPageFactory = false;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend((__('Brands')));

        return $resultPage;
    }
}
