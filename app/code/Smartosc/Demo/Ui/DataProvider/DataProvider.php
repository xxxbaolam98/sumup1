<?php
namespace Smartosc\Demo\Ui\DataProvider;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
public function __construct(
    $name,
    $primaryFieldName,
    $requestFieldName,
    \Smartosc\Demo\Model\ResourceModel\TestSiteMap\CollectionFactory $collectionFactory,
    array $meta = [],
    array $data = []
)
    {
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
