<?php

namespace Smartosc\Brand\Controller\Adminhtml\Brand;

class Delete extends \Magento\Backend\App\Action
{
    protected $_pageFactory;
    protected $_brandFactory;
    protected $_messageManager;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Smartosc\Brand\Model\BrandFactory $brandFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        parent::__construct($context);
        $this->_pageFactory = $pageFactory;
        $this->_brandFactory = $brandFactory;
        $this->_messageManager =$messageManager;
    }

    public function execute()
    {
        $brand_id = $this->getRequest()->getParam('brand_id');
        if (!empty($brand_id)) {
            $brand = $this->_brandFactory->create();
            $collection = $brand->getCollection();
            $brand->load($brand_id);
            $brand->delete();
            $this->_messageManager->addSuccessMessage('Delete Successfully');
        } else {
            $this->_messageManager->addErrorMessage('Brand does not exists');
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('brand/brand/brandlist');
        return $resultRedirect;
    }
}
