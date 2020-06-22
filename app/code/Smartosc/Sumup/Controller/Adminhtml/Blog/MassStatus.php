<?php

namespace Smartosc\Sumup\Controller\Adminhtml\Blog;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use Smartosc\Sumup\Model\ResourceModel\Blog\CollectionFactory;

/**
 * Class MassStatus
 */
class MassStatus extends Action
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Context           $context
     * @param Filter            $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(Context $context, Filter $filter, CollectionFactory $collectionFactory)
    {
        $this->filter            = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * Execute action
     * @return Redirect
     * @throws LocalizedException|Exception
     */
    public function execute()
    {
        $statusValue = $this->getRequest()->getParam('status');
        $collection  = $this->filter->getCollection($this->collectionFactory->create());

        foreach ($collection as $item) {
            $item->setData('status_id', $statusValue);
            $item->save();
        }

        $this->messageManager->addSuccess(__('A total of %1 record(s) have been modified.', $collection->getSize()));

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setPath('sumup/blog/bloglist');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Smartosc_Sumup::massupdateblog');
    }
}
