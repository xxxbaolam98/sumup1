<?php

namespace Smartosc\Sumup\Model\Blog;

class Tag implements \Magento\Framework\Option\ArrayInterface
{
    private $_tagCollectionFactory;

    public function __construct(
        \Smartosc\Sumup\Model\ResourceModel\Tag\CollectionFactory $collectionFactory
    ) {
        $this->_tagCollectionFactory = $collectionFactory;

    }


    public function toOptionArray($addEmpty = true)
    {

        /** @var \Smartosc\Sumup\Model\ResourceModel\Status\CollectionFactory $collection */
        $collection = $this->_tagCollectionFactory->create();

        $options = [];

        if ($addEmpty) {
            $options[] = ['label' => __('-- Please Select a Tag --'), 'value' => ''];
        }
        foreach ($collection as $tag) {
            $options[] = ['label' => $tag->getTagName(), 'value' => $tag->getId()];
        }

        return $options;
    }
}
