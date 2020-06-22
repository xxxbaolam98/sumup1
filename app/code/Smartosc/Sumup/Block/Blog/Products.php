<?php

namespace Smartosc\Sumup\Block\Blog;

use Magento\Backend\Block\Template;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Pricing\Helper\Data as priceHelper;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Smartosc\Sumup\Model\BlogFactory;
use Smartosc\Sumup\Model\Config;
use Magento\Framework\App\ObjectManager;

class Products extends Template
{
    private $_blogFactory;

    private $scopeConfig;

    protected $priceHepler;

    private $_collectionFactory;

    protected $_storeManager;

    private $_productFactory;

    private $_productRepository;

    public function __construct(
        BlogFactory $blogFactory,
        Config $config,
        Registry $registry,
        Template\Context $context,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        priceHelper $priceHepler,
        CollectionFactory $CollectionFactory,
        ProductFactory $productFactory,
        ProductRepository $productRepository
    ) {
        $this->priceHepler = $priceHepler;
        $this->_collectionFactory = $CollectionFactory;
        $this->_blogFactory = $blogFactory;
        $this->_productRepository = $productRepository;
        $this->_productFactory = $productFactory;
        $this->scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    public function displayProducts()
    {
        $blog_id = $this->getRequest()->getParam('blog_id');
        $productCollection = $this->_collectionFactory->create();
        $collection = $this->_blogFactory->create()->getCollection();
        $blog = $collection->getProducts($blog_id);
        $productid_arr = $blog->getProducts();
        if (is_array($productid_arr)) {
            $productCollection->addFieldToFilter('entity_id', ['in' => $productid_arr]);
            $productCollection->addAttributeToSelect('name');
            $productCollection->addAttributeToSelect('price');
            $productCollection->addAttributeToSelect('thumbnail');

            return $productCollection;
        }
        return null;
    }

    public function getProductThumbnail($thumbnail)
    {
        return $this->_getStoreManager()->getStore()->getBaseUrl(
            UrlInterface::URL_TYPE_MEDIA
        ) . "catalog/product/" . $thumbnail;
    }

    public function getFormattedPrice($price)
    {
        return $this->priceHepler->currency(number_format($price, 2), true, false);
    }

    public function getConfig()
    {
        $value = $this->scopeConfig->getValue("sumup/general/enable");
        return $value;
    }

    private function _getStoreManager()
    {
        if ($this->_storeManager === null) {
            $this->_storeManager = ObjectManager::getInstance()->get(StoreManagerInterface::class);
        }
        return $this->_storeManager;
    }
}
