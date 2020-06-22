<?php
namespace Smartosc\Sumup\Ui\DataProvider;

class CategoryDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Smartosc\Sumup\Model\ResourceModel\CategorySumup\Grid\CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        $collection = $this->getCollection();
        $data['items'] = [];
        $data = $collection->toArray();
        return $data;
    }
}
