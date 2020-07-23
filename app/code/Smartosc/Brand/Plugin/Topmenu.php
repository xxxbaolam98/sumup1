<?php
namespace Smartosc\Brand\Plugin;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Topmenu
{
    protected $collectionFactory;

    protected $_storeManager;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    private $brandFactory;

    public function __construct(
        \Magento\Customer\Model\Session $session,
        \Smartosc\Brand\Model\ResourceModel\Brand\CollectionFactory $collectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        \Smartosc\Brand\Model\BrandFactory $brandFactory
    ) {
        $this->brandFactory = $brandFactory;
        $this->Session = $session;
        $this->collectionFactory = $collectionFactory;
        $this->_storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
    }

    public function afterGetHtml(\Magento\Theme\Block\Html\Topmenu $topmenu, $html)
    {
        $storeid =  $this->_storeManager->getStore()->getId();
        $brands = $this->collectionFactory->create();
        $brands->addFieldToFilter('store_id', ['in' => [0,$storeid]]);
        $brands->addFieldToFilter('status', ['eq' => 1]);
        $brands->addFieldToFilter('is_feature', ['eq' => 1]);
        $swappartyUrl = $this->getBrandListUrl();//here you can set link
        $magentoCurrentUrl = $topmenu->getUrl('brand/brand/index', ['_current' => true, '_use_rewrite' => true]);
        if (strpos($magentoCurrentUrl, 'brand/brand/index') !== false) {
            $html .= "<li class=\"level0 nav-5 active level-top parent ui-menu-item\">";
        } else {
            $html .= "<li class=\"level0 nav-4 level-top parent ui-menu-item\">";
        }
        $html .= "<a href=\"" . $swappartyUrl . "\" class=\"level-top ui-corner-all\"><span class=\"ui-menu-icon ui-icon ui-icon-carat-1-e\"></span><span>" . __("Brands") . "</span></a>";
        $html .= "<ul class=\"level0 submenu ui-menu ui-widget ui-widget-content ui-corner-all\" role=\"menu\" aria-expanded=\"false\" style=\"display: none; top: 47px; left: 0.109375px;\" aria-hidden=\"true\">";
        foreach ($brands as $brand) {
            $html .= "<li class=\"level1 nav-5-1 category-item first last ui-menu-item\" role=\"presentation\">
<a href=\" " . $brand->getUrl() . "\" id=\"ui-id-28\" class=\"ui-corner-all\" tabindex=\"-1\" role=\"menuitem\"><span>" . $brand->getName() . "</span></a>
</li>";
        }
        $html .= "</ul></li>";
        return $html;
    }

    public function getBrandUrl()
    {
        return $this->_storeManager->getStore()->getUrl('brand/brand/branddetail') . "brand_id/";
    }

    public function getBrandListUrl()
    {
        $brand = $this->brandFactory->create();
        $brandlist_url = $brand->getBrandListUrlKey();
        $brandlist_key = $this->scopeConfig->getValue('brand/seo/brand_list_url');
        if ($brandlist_key != null && $brandlist_key != "") {
            $brandlist_url = $brandlist_key;
        }
        return $this->_storeManager->getStore()->getBaseUrl() . $brandlist_url;
    }
}
