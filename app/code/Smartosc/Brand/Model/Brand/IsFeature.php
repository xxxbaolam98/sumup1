<?php

namespace Smartosc\Brand\Model\Brand;

class IsFeature implements \Magento\Framework\Option\ArrayInterface
{
    private $_brandFactory;

    public function __construct(
        \Smartosc\Brand\Model\BrandFactory $brandFactory
    ) {
        $this->_brandFactory = $brandFactory;
    }

    public function toOptionArray($addEmpty = true)
    {
        $brand = $this->_brandFactory->create();
        $feat_arr = $brand->getAvailableIsFeature();

        $options = [];

        foreach ($feat_arr as $key => $value) {
            $options[] = ['label' => $value , 'value' => $key];
        }

        return $options;
    }
}
