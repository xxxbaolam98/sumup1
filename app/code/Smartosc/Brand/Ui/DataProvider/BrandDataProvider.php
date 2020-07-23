<?php
namespace Smartosc\Brand\Ui\DataProvider;

class BrandDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    private $productFactory;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Smartosc\Brand\Model\ResourceModel\Sumup\Grid\CollectionFactory $collectionFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->productFactory = $productFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }


}
