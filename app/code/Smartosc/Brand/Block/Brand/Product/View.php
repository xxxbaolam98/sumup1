<?php
namespace Smartosc\Brand\Block\Brand\Product;

class View extends \Magento\Framework\View\Element\Template
{
    protected $_brand;

    protected $_coreRegistry = null;

    protected $_brandHelper;

    protected $_resource;

    protected $_storeManager;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Smartosc\Brand\Helper\Data $brandHelper
     * @param \Smartosc\Brand\Model\BrandFactory $brandCollection
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Smartosc\Brand\Helper\Data $brandHelper,
        \Smartosc\Brand\Model\BrandFactory $brand,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->_storeManager = $storeManager;
        $this->_brand = $brand;
        $this->_brandHelper = $brandHelper;
        $this->_coreRegistry = $registry;
        $this->_resource = $resource;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve current product model
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        return $this->_coreRegistry->registry('current_product');
    }

    public function getBrandCollection()
    {
        $storeid =  $this->_storeManager->getStore()->getId();
        $product = $this->getProduct();
        $connection = $this->_resource->getConnection();
        $table_name = $this->_resource->getTableName('smartosc_brand_product');
        $brandIds = $connection->fetchCol(" SELECT brand_id FROM " . $table_name . " WHERE product_id = " . $product->getId());
        if ($brandIds || count($brandIds) > 0) {
            $brand = $this->_brand->create();
            $collection = $brand->getCollection()
                ->setOrder('position', 'ASC')
                ->addFieldToFilter('status', 1)
                ->addFieldToFilter('store_id', ['in' => [0,$storeid]])
                ->addFieldToFilter('brand_id', ['in' => $brandIds]);

            return $collection;
        }
        return false;
    }

    public function _toHtml()
    {
        return parent::_toHtml();
    }
}
