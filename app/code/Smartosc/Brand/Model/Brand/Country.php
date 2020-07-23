<?php

namespace Smartosc\Brand\Model\Brand;

class Country implements \Magento\Framework\Option\ArrayInterface
{
    private $_countryFactory;

    public function __construct(
        \Magento\Directory\Model\CountryFactory $countryFactory
    ) {
        $this->_countryFactory = $countryFactory;

    }


    public function toOptionArray($addEmpty = true)
    {

        $country = $this->_countryFactory->create();
        $collection = $country->getCollection();

        $options = [];

//        if ($addEmpty) {
//            $options[] = ['label' => __('-- Please Select a Country --'), 'value' => ''];
//        }
        foreach ($collection as $country) {
            $options[] = ['label' => $country->getName(), 'value' => $country->getCountryId()];
        }

        return $options;
    }
}
