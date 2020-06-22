<?php

namespace Smartosc\Sumup\Ui\DataProvider\Form;

use Magento\Catalog\Model\ResourceModel\Product\Collection;

class ProductDataProvider extends \Magento\Catalog\Ui\DataProvider\Product\ProductDataProvider
{
    public function getCollection()
    {
        /** @var Collection $collection */
        $collection = parent::getCollection();
        return $collection->addAttributeToSelect('status');
    }
}
