<?php

namespace Smartosc\Sumup\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Url as UrlManager;
use Magento\Store\Model\StoreManagerInterface;
use Smartosc\Sumup\Model\BlogFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Url
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var BlogFactory
     */
    protected $blogFactory;

    /**
     * @var CategoryFactory
     */
    protected $categoryFactory;

    /**
     * @var UrlManager
     */
    protected $urlManager;

    /**
     * @var Config
     */
    protected $config;

    public function __construct(
        StoreManagerInterface $storeManager,
        Config $config,
        ScopeConfigInterface $scopeConfig,
        BlogFactory $blogFactory,
        CategoryFactory $categoryFactory,
        UrlManager $urlManager
    ) {
        $this->storeManager = $storeManager;
        $this->config = $config;
        $this->scopeConfig = $scopeConfig;
        $this->blogFactory = $blogFactory;
        $this->categoryFactory = $categoryFactory;
        $this->urlManager = $urlManager;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->urlManager->getUrl($this->config->getBaseRoute());
    }

    /**
     * @param $post
     * @param bool $useSid
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBlogUrl($post, $useSid = true)
    {
        $storeCode = $this->storeManager->getStore($post->getStoreId())->getCode();
        return $this->getUrl(
            '/' . $post->getUrlKey(),
            'blog',
            ['_nosid' => !$useSid, '_scope' => $storeCode]
        );
    }

    /**
     * @param string $route
     * @param string $type
     * @param array $urlParams
     *
     * @return string
     */
    protected function getUrl($route, $type, $urlParams = [])
    {
        $url = $this->urlManager->getUrl($this->config->getBaseRoute() . $route, $urlParams);
        var_dump($url);
        if ($type == 'blog' && $this->config->getPostUrlSuffix()) {
            $url = $this->addSuffix($url, $this->config->getPostUrlSuffix());
        }

        return $url;
    }

    /**
     * @param string $url
     * @param string $suffix
     *
     * @return string
     */
    private function addSuffix($url, $suffix)
    {
        $parts = explode('?', $url, 2);
        $parts[0] = rtrim($parts[0], '/') . $suffix;

        return implode('?', $parts);
    }



    /**
     * @param array $urlParams
     *
     * @return string
     */
    public function getSearchUrl($urlParams = [])
    {
        return $this->getUrl('/sumup/blog/bloglist', 'search', $urlParams);
    }


    /**
     * Return url without suffix
     *
     * @param string $key
     *
     * @return string
     */
    protected function trimSuffix($key)
    {
        $suffix = $this->config->getPostUrlSuffix();
        //user can enter .html or html suffix
        if ($suffix != '' && $suffix[0] != '.') {
            $suffix = '.' . $suffix;
        }

        $key = str_replace($suffix, '', $key);

        return $key;
    }
}
