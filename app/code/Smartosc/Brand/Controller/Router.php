<?php

namespace Smartosc\Brand\Controller;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;

class Router implements \Magento\Framework\App\RouterInterface
{
    const BRAND_ROUTER = 'brand';
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var \Smartosc\Brand\Model\ResourceModel\Brand\CollectionFactory
     */
    private $brandCollectionFactory;
    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    private $actionFactory;

    private $brandFactory;

    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        ScopeConfigInterface $scopeConfig,
        \Smartosc\Brand\Model\ResourceModel\Brand\CollectionFactory $brandCollectionFactory,
        \Smartosc\Brand\Model\BrandFactory $brandFactory
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->brandCollectionFactory = $brandCollectionFactory;
        $this->actionFactory = $actionFactory;
        $this->brandFactory = $brandFactory;
    }

    /**
     * @param RequestInterface $request
     * @return ActionInterface
     */
    public function match(RequestInterface $request)
    {
        $brand = $this->brandFactory->create();
        $brandlist_url = trim($brand->getBrandListUrlKey(), "/ ");
        $brandlist_key = trim($this->scopeConfig->getValue('brand/seo/brand_list_url'), "/ ");
        if ($brandlist_key != null && $brandlist_key != "") {
            $brandlist_url = $brandlist_key;
        }
        $suffix = $this->scopeConfig->getValue('brand/seo/url_suffix');
        $prefix = $this->scopeConfig->getValue('brand/seo/url_prefix');

        $identifier = trim($request->getPathInfo(), '/');

        if ($brandlist_url == $identifier) {
            $request->setModuleName('brand')->setControllerName('brand')->setActionName('index');
            $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $identifier);
            return $this->actionFactory->create(\Magento\Framework\App\Action\Forward::class);
        } else {
            $requestPath0 = str_replace($suffix, '', $identifier);
            $requestPath1 = str_replace($prefix, '', $requestPath0);
            $requestPath1 = trim($requestPath1, '/. ');

            $brandCollection = $this->brandCollectionFactory->create();
            $brandCollection->addFieldToFilter('url_key', ['eq'=> $requestPath1]);
            if (!$brandCollection->getSize()) {
                $request->setModuleName('brand')->setControllerName('brand')->setActionName('index');
            } else {
                $brand = $brandCollection->getFirstItem();
                $request->setModuleName('brand')
                        ->setControllerName('brand')
                        ->setActionName('branddetail')->setParam('brand_id', $brand->getId());
            }
            $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $identifier);
            return $this->actionFactory->create(\Magento\Framework\App\Action\Forward::class);
        }
    }
}
