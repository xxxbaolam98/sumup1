<?php

namespace Smartosc\Sumup\Controller\Adminhtml\Blog;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Smartosc\Sumup\Model\ResourceModel\Blog\CollectionFactory;

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
        $blogCollection = $this->collectionFactory->create();
        $collection     = $this->filter->getCollection($blogCollection);
        $collectionSize = $collection->getSize();
        foreach ($collection as $item) {
            $blogCollection->deleteBlogCategory($item->getId());
            $blogCollection->deleteBlogTag($item->getId());
            $blogCollection->deleteBlogProducts($item->getId());
            $item->delete();
        }

        $this->messageManager->addSuccess(__('A total of %1 post(s) have been deleted.', $collectionSize));

        /**
         * @var Redirect $resultRedirect
         */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setPath('sumup/blog/bloglist');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Smartosc_Sumup::massdeleteblog');
    }
}
