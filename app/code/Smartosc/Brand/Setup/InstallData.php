<?php

namespace Smartosc\Brand\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Smartosc\Brand\Model\BrandFactory;
use Magetop\Brand\Model\Brand;

class InstallData implements InstallDataInterface
{

    protected $eavSetupFactory;

    public function __construct(
        EavSetupFactory $eavSetupFactory
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $data = [
            'group' => 'General',
            'type' => 'varchar',
            'input' => 'multiselect',
            'default' => '',
            'label' => 'Product Brand',
            'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
            'frontend' => '',
            'source' => 'Smartosc\Brand\Model\Brandlist',
            'visible' => 1,
            'required' => 1,
            'user_defined' => 1,
            'used_for_price_rules' => 1,
            'position' => 2,
            'unique' => 0,
            'sort_order' => 100,
            'is_global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
            'is_required' => 0,
            'is_configurable' => 1,
            'is_searchable' => 0,
            'is_visible_in_advanced_search' => 0,
            'is_comparable' => 0,
            'is_filterable' => 0,
            'is_filterable_in_search' => 1,
            'is_used_for_promo_rules' => 1,
            'is_html_allowed_on_front' => 0,
            'is_visible_on_front' => 1,
            'used_in_product_listing' => 1,
            'used_for_sort_by' => 0,
        ];
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'product_brand',
            $data
        );
    }
}
