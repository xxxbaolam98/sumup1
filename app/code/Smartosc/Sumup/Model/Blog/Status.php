<?php

namespace Smartosc\Sumup\Model\Blog;

class Status implements \Magento\Framework\Option\ArrayInterface
{
    private $_statusCollectionFactory;

    public function __construct(
        \Smartosc\Sumup\Model\ResourceModel\Status\CollectionFactory $collectionFactory
    ) {
        $this->_statusCollectionFactory = $collectionFactory;

    }


    public function toOptionArray($addEmpty = true)
    {

        /** @var \Smartosc\Sumup\Model\ResourceModel\Status\CollectionFactory $collection */
        $collection = $this->_statusCollectionFactory->create();

        $options = [];

        if ($addEmpty) {
            $options[] = ['label' => __('-- Please Select a Status --'), 'value' => ''];
        }
        foreach ($collection as $status) {
            $options[] = ['label' => $status->getStatusName(), 'value' => $status->getId()];
        }

        return $options;
    }
}
