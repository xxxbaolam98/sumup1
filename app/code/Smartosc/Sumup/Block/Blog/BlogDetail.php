<?php

namespace Smartosc\Sumup\Block\Blog;

use Magento\Backend\Block\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use Smartosc\Sumup\Model\BlogFactory;
use Smartosc\Sumup\Model\Config;
use Smartosc\Sumup\Model\ResourceModel\Blog\CollectionFactory;
use Smartosc\Sumup\Model\Url;

class BlogDetail extends Template
{
    private $_blogFactory;

    private $scopeConfig;
    
    private $blogCollectionFactory;

    private $url;

    protected $_storeManager;

    protected $_urlInterface;

    public function __construct(
        BlogFactory $blogFactory,
        Config $config,
        Registry $registry,
        Template\Context $context,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        CollectionFactory $blogCollectionFactory,
        Url $url,
        \Magento\Framework\UrlInterface $urlInterface
    ) {
        $this->url = $url;
        $this->_blogFactory = $blogFactory;
        $this->scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
        $this->_urlInterface = $urlInterface;
        $this->blogCollectionFactory = $blogCollectionFactory;
        parent::__construct($context);
    }

    public function displayBlog()
    {
        $blog_id = $this->getRequest()->getParam('blog_id');
        $collection = $this->blogCollectionFactory->create();
        $output = $collection->getTagCategory($blog_id);
        return $output;
    }


    public function getConfig()
    {
        $value = $this->scopeConfig->getValue("sumup/general/enable");
        return $value;
    }


}
