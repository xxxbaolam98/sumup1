<?php

namespace Smartosc\Brand\Block;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Brandpage extends \Magento\Framework\View\Element\Template
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Smartosc\Brand\Helper\Data
     */
    protected $_brandHelper;

    /**
     * @var \Smartosc\Brand\Model\Brand
     */
    protected $_brand;

    protected $_collection;

    protected $_storeManager;

    private $scopeConfig;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Smartosc\Brand\Helper\Data $brandHelper
     * @param \Smartosc\Brand\Model\BrandFactory $brand
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Smartosc\Brand\Helper\Data $brandHelper,
        \Smartosc\Brand\Model\BrandFactory $brand,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->_storeManager = $storeManager;
        $this->_brand = $brand;
        $this->_coreRegistry = $registry;
        $this->_brandHelper = $brandHelper;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    public function _construct()
    {
        parent::_construct();

        $template = 'Smartosc_Brand::brandlistpage_grid.phtml';
        $this->setTemplate($template);
    }

    public function getBrandCollection()
    {
        $storeid =  $this->_storeManager->getStore()->getId();
        $groupid = $this->getParam();
        if ($groupid === "00") {
            $groupid = "0";
        }
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = 5;
        $brand = $this->_brand->create();
        $collection = $brand->getCollection();
        $collection->getSelect()->reset(\Magento\Framework\DB\Select::ORDER);
        $collection->setOrder('position', 'ASC');
        $collection->addFieldToFilter('status', 1);
        $collection->addFieldToFilter('store_id', ['in' => [0,$storeid]]);
        $collection->addFieldToFilter('name', ['like' => $groupid . '%']);
        $collection->setPageSize($pageSize);
        $collection->setCurPage($page);
        $this->_collection = $collection;
        return $this->_collection;
    }

    public function getConfig($key, $default = '')
    {
        $result = $this->_brandHelper->getConfig($key);
        if (!$result) {
            return $default;
        }
        return $result;
    }

    public function groupBrandByFirstChar()
    {
        $brand = $this->_brand->create();
        $brandCollection = $brand->getCollection();
        $firstChar_arr = [];
        foreach ($brandCollection as $brand) {
            $name = $brand->getName();
            if (!is_numeric($name[0])) {
                $name = strtolower($name);
            }
            if (!in_array($name[0], $firstChar_arr)) {
                array_push($firstChar_arr, $name[0]);
            }
        }
        $firstChars = implode(",", $firstChar_arr);
        $firstChars = strtoupper($firstChars);
        $arr = explode(",", $firstChars);
        natsort($arr);
        return $arr;
    }

//    public function BrandInGroup($first_char)
//    {
//        $storeid =  $this->_storeManager->getStore()->getId();
//        $brand = $this->_brand->create();
//        $brandCollection = $brand->getCollection();
//        $brandCollection->addFieldToFilter('name', ['like' => $first_char . '%']);
//        $brandCollection->setOrder('position', 'ASC');
//        $brandCollection->addFieldToFilter('status', 1);
//        $brandCollection->addFieldToFilter('store_id', ['in' => [0,$storeid]]);
//        return $brandCollection;
//    }

    public function getGroupUrl($letter)
    {
        return $this->getBrandListUrl() . "?group=" . $letter;
    }

    /**
     * Prepare global layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->pageConfig->addBodyClass('brand-brandlist');
        $this->pageConfig->getTitle()->set('Brands');
        if ($this->getBrandCollection()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'brandgroup.history.pager'
            )->setAvailableLimit([5 => 5, 10 => 10, 15 => 15, 20 => 20])
                ->setShowPerPage(false)->setCollection(
                    $this->getBrandCollection()
                );
            $this->setChild('pager', $pager);
            $this->getBrandCollection()->load();
        }
        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getAlphaNumeric()
    {
        $al = range('A', 'Z');
        $num = ["00", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
        $alphanum = array_merge($al, $num);
        return $alphanum;
    }

    public function getParam()
    {
        $group =($this->getRequest()->getParam('group')) ? $this->getRequest()->getParam('group') : "";
        return $group;
    }

    public function getBrandListUrl()
    {
        $brand = $this->_brand->create();
        $brandlist_url = $brand->getBrandListUrlKey();
        $brandlist_key = $this->scopeConfig->getValue('brand/seo/brand_list_url');
        if ($brandlist_key != null && $brandlist_key != "") {
            $brandlist_url = $brandlist_key;
        }
        return $this->_storeManager->getStore()->getBaseUrl() . $brandlist_url;
    }
}
