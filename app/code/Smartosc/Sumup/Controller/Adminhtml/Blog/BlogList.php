<?php

namespace Smartosc\Sumup\Controller\Adminhtml\Blog;

class BlogList extends \Magento\Backend\App\Action
{
    protected $resultPageFactory = false;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend((__('Blogs')));

        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Smartosc_Sumup::bloglist');
    }

 }
