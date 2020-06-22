<?php
namespace Smartosc\Demo\Block;
use Magento\Store\Model\StoreManagerInterface;

class Sitemap extends \Magento\Framework\View\Element\Template
{
    /**
     * @var QuoteEmail
     */
    private $storeManager;
    protected $_testsitemapFactory;
    private $scopeConfig;


    public function __construct(StoreManagerInterface $storeManager,\Magento\Framework\View\Element\Template\Context $context,  array $data = [],
                                \Smartosc\Demo\Model\TestSiteMapFactory $testsitemapFactory, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->storeManager = $storeManager;
        $this->_testsitemapFactory = $testsitemapFactory;
        $this->scopeConfig = $scopeConfig;

        parent::__construct($context,$data);
    }

    public function sitemaplist()
    {
        $sitemap = $this->_testsitemapFactory->create();
        $sitemap_collection = $sitemap->getCollection();

        return $sitemap_collection;
    }


    public function getConfig() {
        $value = $this->scopeConfig->getValue("helloworld/general/enable");
        return $value;
    }



}
