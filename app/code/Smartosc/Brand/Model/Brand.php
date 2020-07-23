<?php
namespace Smartosc\Brand\Model;

/**
 * Brand Model
 */
class Brand extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Brand's Statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    const BRAND_LIST_URL_KEY = "brand-list.html";

    /**
     * Product collection factory
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /** @var \Magento\Store\Model\StoreManagerInterface */
    protected $_storeManager;

    /**
     * URL Model instance
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $_url;

    /**
     * @var \Magento\Catalog\Helper\Category
     */
    protected $_brandHelper;

    protected $countryFactory;

    /**
     * @param \Magento\Framework\Model\Context                          $context
     * @param \Magento\Framework\Registry                               $registry
     * @param \Smartosc\Brand\Model\ResourceModel\Brand|null                      $resource
     * @param \Smartosc\Brand\Model\ResourceModel\Brand\Collection|null           $resourceCollection
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Store\Model\StoreManagerInterface                $storeManager
     * @param \Magento\Framework\UrlInterface                           $url
     * @param \Smartosc\Brand\Helper\Data                                    $brandHelper
     * @param array                                                     $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\UrlInterface $url,
        \Smartosc\Brand\Helper\Data $brandHelper,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        \Smartosc\Brand\Model\ResourceModel\Brand $resource = null,
        \Smartosc\Brand\Model\ResourceModel\Brand\Collection $resourceCollection = null,
        array $data = []
    ) {
        $this->countryFactory = $countryFactory;
        $this->_storeManager = $storeManager;
        $this->_url = $url;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_brandHelper = $brandHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Initialize customer model
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Smartosc\Brand\Model\ResourceModel\Brand');
    }

    /**
     * Prepare page's statuses.
     * Available event cms_page_get_available_statuses to customize statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => 'Enabled', self::STATUS_DISABLED => 'Disabled'];
    }

    public function getAvailableIsFeature()
    {
        return [1 => 'Featured', 0 => 'Not Featured'];
    }

    /**
     * Check if page identifier exist for specific store
     * return page id if page exists
     *
     * @param string $identifier
     * @param int $storeId
     * @return int
     */
    public function checkIdentifier($identifier, $storeId)
    {
        return $this->_getResource()->checkIdentifier($identifier, $storeId);
    }

    /**
     * Get category products collection
     *
     * @return \Magento\Framework\Data\Collection\AbstractDb
     */
    public function getProductCollection()
    {
        $collection = $this->_productCollectionFactory->create()->addAttributeToSelect('*')->addAttributeToFilter('product_brand', ['eq'=>$this->getId()]);
        return $collection;
    }

    public function getUrl()
    {
        $url = $this->_storeManager->getStore()->getBaseUrl();
        $url_prefix = trim($this->_brandHelper->getConfig('seo/url_prefix'), '/ ');
        $urlPrefix = '';
        if ($url_prefix && !empty($url_prefix)) {
            $urlPrefix = $url_prefix . '/';
        }
        $url_suffix = trim($this->_brandHelper->getConfig('seo/url_suffix'), '/ ');
        $urlSuffix = '';
        if ($url_suffix && !empty($url_suffix)) {
            $urlSuffix = "." . $url_suffix;
        }
        return $url . $urlPrefix . $this->getUrlKey() . $urlSuffix;
    }

    /**
     * Retrive image URL
     *
     * @return string
     */
    public function getImageUrl()
    {
        $url = false;
        $image = $this->getImage();
        if (empty($image) || $image == null) {
            $image = 'placeholder.jpg';
        }
        if ($image) {
            $url = $this->_storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            ) . 'smartosc/tmp/sumup/' . $image;
        }
        return $url;
    }

    public function loadByBrandName($brand_name = "")
    {
        if ($brand_name) {
            $brand_id = $this->_getResource()->getBrandIdByName($brand_name);
            if ($brand_id) {
                $this->load((int)$brand_id);
            }
        }
        return $this;
    }

    public function saveProduct($product_id = "0")
    {
        if ($product_id) {
            $this->_getResource()->saveProduct($this, $product_id);
        }
        return $this;
    }

    public function deleteBrandsByProduct($product_id = "0")
    {
        if ($product_id) {
            $this->_getResource()->deleteBrandsByProduct($product_id);
        }
        return $this;
    }

    /**
     * Retrive thumbnail URL
     *
     * @return string
     */
    public function getThumbnailUrl()
    {
        $url = false;
        $thumbnail = $this->getThumbnail();
        if (empty($thumbnail) || $thumbnail == null) {
            $thumbnail = 'placeholder.jpg';
        }
        $url = $this->_storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        ) . 'smartosc/tmp/sumup/' . $thumbnail;

        return $url;
    }

    public function getBrandListUrlKey()
    {
        return self::BRAND_LIST_URL_KEY;
    }

    public function getName()
    {
        return $this->getData('name');
    }

    public function getDescription()
    {
        return $this->getData('description');
    }

    public function getUrlKey()
    {
        return $this->getData('url_key');
    }

    public function getStatus()
    {
        return $this->getData('status');
    }

    public function getImage()
    {
        return $this->getData('image');
    }

    public function getThumbnail()
    {
        return $this->getData('thumbnail');
    }

    public function getPosition()
    {
        return $this->getData('position');
    }

    public function getCountryId()
    {
        return $this->getData('country_id');
    }

    public function getCountryName()
    {
        $countryName = '';
        $country = $this->countryFactory->create()->loadByCode($this->getCountryId());
        if ($country) {
            $countryName = $country->getName();
        }
        return $countryName;
    }

    public function getPageLayout()
    {
        return $this->getData('page_layout');
    }

    public function getLayoutUpdateXml()
    {
        return $this->getData('layout_update_xml');
    }

    public function getProducts()
    {
        return $this->getData('products');
    }

    public function getStores()
    {
        return $this->getData('store_ids');
    }

    public function getIsFeature()
    {
        return $this->getData('is_feature');
    }

    public function setIsFeature($is_feature)
    {
        return $this->setData('is_feature', $is_feature);
    }

    public function setName($name)
    {
        return $this->setData('name', $name);
    }

    public function setDescription($des)
    {
        return $this->setData('description', $des);
    }

    public function setUrlKey($urlkey)
    {
        return $this->setData('url_key', $urlkey);
    }

    public function setStatus($status)
    {
        return $this->setData('status', $status);
    }

    public function setImage($image)
    {
        return $this->setData('image', $image);
    }

    public function setThumbnail($thumb)
    {
        $this->setData('thumbnail', $thumb);
    }

    public function setPosition($pos)
    {
        $this->setData('position', $pos);
    }

    public function setCountryId($country)
    {
        $this->setData('country_id', $country);
    }

    public function setPageLayout($layout)
    {
        $this->setData('page_layout', $layout);
    }

    public function setLayoutUpdateXml($xml)
    {
        $this->setData('layout_update_xml', $xml);
    }
}
