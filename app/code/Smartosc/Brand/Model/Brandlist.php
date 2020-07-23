<?php

namespace Smartosc\Brand\Model;

class Brandlist extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    protected $_brand;

    /**
     *
     * @param \Smartosc\Brand\Model\Brand $brand
     */
    public function __construct(
        \Smartosc\Brand\Model\Brand $brand
    ) {
        $this->_brand = $brand;
    }

    /**
     * Get Gift Card available templates
     *
     * @return array
     */
    public function getAvailableTemplate()
    {
        $brands = $this->_brand->getCollection()
            ->addFieldToFilter('status', '1');
        $listBrand = [];
        foreach ($brands as $brand) {
            $listBrand[] = ['label' => $brand->getName(),
                'value' => $brand->getId()];
        }
        return $listBrand;
    }

    /**
     * Get model option as array
     *
     * @return array
     */
    public function getAllOptions($withEmpty = true)
    {
        $options = [];
        $options = $this->getAvailableTemplate();

        return $options;
    }
}
