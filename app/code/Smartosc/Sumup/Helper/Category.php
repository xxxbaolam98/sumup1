<?php

namespace Smartosc\Sumup\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Smartosc\Sumup\Model\ResourceModel\Category\CollectionFactory;

class Category extends AbstractHelper
{

    protected $categoryCollectionFactory;

    /**
     * @param CollectionFactory $categoryCollectionFactory
     * @param Context                         $context
     */
    public function __construct(
        CollectionFactory $categoryCollectionFactory,
        Context $context
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;

        parent::__construct($context);
    }

    /**
     * @return \Smartosc\Sumup\Model\Category|false
     */
    public function getRootCategory()
    {
        $category   = false;
        $collection = $this->categoryCollectionFactory->create()
            ->addFieldToFilter('parent_id', 0);

        if ($collection->count()) {
            $category = $collection->getFirstItem();
        }

        return $category;
    }
}
