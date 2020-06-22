<?php

namespace Smartosc\Sumup\Model\Blog;

class Category implements \Magento\Framework\Option\ArrayInterface
{
    private $_categoryCollectionFactory;

    public function __construct(
        \Smartosc\Sumup\Model\ResourceModel\Category\CollectionFactory $collectionFactory
    ) {
        $this->_categoryCollectionFactory = $collectionFactory;

    }


    public function toOptionArray($addEmpty = true)
    {

        /** @var \Smartosc\Sumup\Model\ResourceModel\Category\CollectionFactory $collection */
        $collection = $this->_categoryCollectionFactory->create();
        $options = [];
        foreach ($collection as $category) {
            $options[] = ['label' => $category->getCategoryName(), 'value' => $category->getId()];
        }

        return $options;
    }
}
