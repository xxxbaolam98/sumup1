<?php

namespace Smartosc\Sumup\Controller\Adminhtml\Category;

class Edit extends \Magento\Backend\App\Action
{
    protected $resultPageFactory = false;

    private $registry;

    private $categoryFactory;


    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Smartosc\Sumup\Model\CategoryFactory $categoryFactory,
        \Magento\Framework\Registry $registry
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->categoryFactory = $categoryFactory;
        $this->registry = $registry;
    }

    public function execute()
    {

        /**
         * init Model using Model Factory
         */
        $categoryModel= $this->categoryFactory->create();
        /**
         * for  update a row data, we need  primary  field value
         * which URL param "example_id" = Database example table "id" field
         */
        $id = $this->getRequest()->getParam('category_id');
        if($id)
        {
            /**
             * Load a record data from data using model
             */
            $categoryModel->load($id);
            /**
             * Redirect to listing page if a record does not exit at database
             * with request parameter
             */
            if(!$categoryModel->getId()){
                $resultRedirect =  $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('sumup/category/categorylist');
            }
        }

        /**
         * Save Model Data to a registry variable for future purpose
         * Variable name is user defined
         */
        $this->registry->register('sumup_category',$categoryModel);

        $resultPage =$this->resultPageFactory->create();
        $resultPage->getConfig()->setKeywords(__('Edit Page'));
        /**
         * Left menu Select
         */
        $resultPage->setActiveMenu('Smartosc_Sumup::menu');

        $pageTitltPrefix = __('%1',
            $categoryModel->getId()?$categoryModel->getData('category_name'): __('New Category')
        );
        $resultPage->getConfig()->getTitle()->prepend($pageTitltPrefix);
        return $resultPage;
    }
}
