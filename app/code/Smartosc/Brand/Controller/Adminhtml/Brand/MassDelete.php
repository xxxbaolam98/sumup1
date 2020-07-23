<?php

namespace Smartosc\Brand\Controller\Adminhtml\Brand;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Smartosc\Brand\Model\ResourceModel\Brand\CollectionFactory;

class MassDelete extends Action
{
    protected $filter;

    protected $collectionFactory;

    public function __construct(Context $context, Filter $filter, CollectionFactory $collectionFactory)
    {
        $this->filter            = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $brandCollection = $this->collectionFactory->create();
        $collection     = $this->filter->getCollection($brandCollection);
        $collectionSize = $collection->getSize();
        foreach ($collection as $item) {

            $item->delete();
        }

        $this->messageManager->addSuccess(__('A total of %1 post(s) have been deleted.', $collectionSize));

        /**
         * @var Redirect $resultRedirect
         */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setPath('brand/brand/brandlist');
    }

}
