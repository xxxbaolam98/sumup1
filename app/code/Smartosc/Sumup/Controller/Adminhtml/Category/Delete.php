<?php

namespace Smartosc\Sumup\Controller\Adminhtml\Category;

class Delete extends \Magento\Backend\App\Action
{
    protected $_pageFactory;
    protected $_categoryFactory;
    protected $_messageManager;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Smartosc\Sumup\Model\CategoryFactory $categoryFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager
    )
    {
        parent::__construct($context);
        $this->_pageFactory = $pageFactory;
        $this->_categoryFactory = $categoryFactory;
        $this->_messageManager =$messageManager;
    }

    public function execute()
    {
        $category_id = $this->getRequest()->getParam('category_id');
        if(!empty($category_id))
        {
            $category = $this->_categoryFactory->create();
            $category->load($category_id);
            $category->delete();
            $this->_messageManager->addSuccessMessage('Delete Successfully');
        } else {
            $this->_messageManager->addErrorMessage('Category does not exists');
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('sumup/category/categorylist');
        return $resultRedirect;
    }


}
