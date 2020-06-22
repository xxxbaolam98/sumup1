<?php

namespace Smartosc\Sumup\Controller\Adminhtml\Category;

class Save extends \Magento\Backend\App\Action
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
        $arr = $this->getRequest()->getParams();
        $category_name = $arr['category_name'];
        $parent_id = 0;
        if (array_key_exists('parent_id', $arr)) {
            $parent_id = $arr['parent_id'];
        }
        if($this->validation($arr) == true) {
        $category = $this->_categoryFactory->create();
        if(array_key_exists('category_id', $arr) && !empty($arr['category_id'])){
            $category = $category->load($arr['category_id']);
        }
        $category->setData("category_name",$category_name);
        $category->setData("parent_id", $parent_id);
        $category->save();
        $this->_messageManager->addSuccessMessage('Save Successfully');
        } else {
            $this->_messageManager->addErrorMessage('Invalid Input');
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        $category_id = $category->getId();
        $resultRedirect->setPath('sumup/category/edit',["category_id"=> $category_id]);
        return $resultRedirect;
    }

    public function validation($arr) {
        if(!array_key_exists('category_name',$arr) || empty($arr['category_name'])) {
            return false;
        }
        return true;
    }


}
