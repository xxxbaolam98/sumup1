<?php

namespace Smartosc\Sumup\Controller\Adminhtml\Blog;

class Edit extends \Magento\Backend\App\Action
{
    protected $resultPageFactory = false;

    private $registry;

    private $blogFactory;


    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Smartosc\Sumup\Model\BlogFactory $blogFactory,
        \Magento\Framework\Registry $registry
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->blogFactory = $blogFactory;
        $this->registry = $registry;
    }

    public function execute()
    {

        /**
         * init Model using Model Factory
         */
        $blogModel= $this->blogFactory->create();
        /**
         * for  update a row data, we need  primary  field value
         * which URL param "example_id" = Database example table "id" field
         */
        $id = $this->getRequest()->getParam('blog_id');
        if($id)
        {
            /**
             * Load a record data from data using model
             */
            $blogModel->load($id);
            /**
             * Redirect to listing page if a record does not exit at database
             * with request parameter
             */
            if(!$blogModel->getId()){
                $resultRedirect =  $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('sumup/blog/bloglist');
            }
        }

        /**
         * Save Model Data to a registry variable for future purpose
         * Variable name is user defined
         */
        $this->registry->register('sumup_blog',$blogModel);

        $resultPage =$this->resultPageFactory->create();
        $resultPage->getConfig()->setKeywords(__('Edit Page'));
        /**
         * Left menu Select
         */
        $resultPage->setActiveMenu('Smartosc_Sumup::menu');

        $pageTitltPrefix = __('%1',
            $blogModel->getId()?$blogModel->getData('name'): __('New Blog')
        );
        $resultPage->getConfig()->getTitle()->prepend($pageTitltPrefix);
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Smartosc_Sumup::createblog');
    }
}
