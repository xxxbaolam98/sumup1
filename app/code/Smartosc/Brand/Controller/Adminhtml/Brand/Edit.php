<?php

namespace Smartosc\Brand\Controller\Adminhtml\Brand;

class Edit extends \Magento\Backend\App\Action
{
    protected $resultPageFactory = false;

    private $registry;

    private $brandFactory;


    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Smartosc\Brand\Model\BrandFactory $brandFactory,
        \Magento\Framework\Registry $registry
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->brandFactory = $brandFactory;
        $this->registry = $registry;
    }

    public function execute()
    {

        /**
         * init Model using Model Factory
         */
        $brandModel = $this->brandFactory->create();
        /**
         * for  update a row data, we need  primary  field value
         * which URL param "example_id" = Database example table "id" field
         */
        $id = $this->getRequest()->getParam('brand_id');
        if ($id) {
            /**
             * Load a record data from data using model
             */
            $brandModel->load($id);
            /**
             * Redirect to listing page if a record does not exit at database
             * with request parameter
             */
            if (!$brandModel->getId()) {
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('brand/brand/brandlist');
            }
        }

        /**
         * Save Model Data to a registry variable for future purpose
         * Variable name is user defined
         */
        $this->registry->register('sumup_brand', $brandModel);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->setKeywords(__('Edit Page'));
        /**
         * Left menu Select
         */
        $resultPage->setActiveMenu('Smartosc_Brand::menu');

        $pageTitltPrefix = __('%1',
            $brandModel->getId() ? $brandModel->getData('name') : __('New Brand')
        );
        $resultPage->getConfig()->getTitle()->prepend($pageTitltPrefix);
        return $resultPage;
    }
}
