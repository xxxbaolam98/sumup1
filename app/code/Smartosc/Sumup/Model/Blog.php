<?php
namespace Smartosc\Sumup\Model;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use Smartosc\Sumup\Model\Blog\FileInfo;

class Blog extends \Magento\Framework\Model\AbstractModel
{
    protected $_storeManager;

    protected $url;

    public function __construct(
        Config $config,
        Url $url,
        StoreManagerInterface $storeManager,
        Context $context,
        Registry $registry
    ) {
        $this->config                   = $config;
        $this->url                      = $url;
        $this->_storeManager             = $storeManager;

        parent::__construct($context, $registry);
    }

    protected function _construct()
    {
        $this->_init('Smartosc\Sumup\Model\ResourceModel\Blog');
    }

    public function getImageUrl()
    {
        $url = '';
        $image = "";
        if ($this->getData('thumbnail_path') != null) {
            $image = $this->getData('thumbnail_path');
        } else {
            $image = 'placeholder.jpg';
        }
        if (!$image) {
            $image = $this->getData('thumbnail');
        }
        if ($image) {
            if (is_string($image)) {
                $url = $this->_getStoreManager()->getStore()->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                ) . FileInfo::ENTITY_MEDIA_PATH . '/' . $image;
            } else {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Something went wrong while getting the image url.')
                );
            }
        }
        return $url;
    }

    public function getName()
    {
        return $this->getData('name');
    }

    public function getShortDescription()
    {
        return $this->getData('short_description');
    }

    public function getDescription()
    {
        return $this->getData('description');
    }

    public function getContent()
    {
        return $this->getData('content');
    }

    public function getPublishDateFrom()
    {
        return $this->getData('publish_date_from');
    }

    public function getPublishDateTo()
    {
        return $this->getData('publish_date_to');
    }

    public function getUrlKey()
    {
        return $this->getData('url_key');
    }

    public function getCategory()
    {
        return $this->getData('category_names');
    }

    public function getTag()
    {
        return $this->getData('tag_names');
    }

    public function getProducts()
    {
        if (!empty($this->getData('products'))) {
            return $this->getData('products');
        }
        return "";
    }

    public function getTagNames()
    {
        if (!empty($this->getTag())) {
            $tags = implode(", ", $this->getTag());
            return $tags;
        }
        return " ";
    }
    public function getCategoryNames()
    {
        $categories = implode(", ", $this->getCategory());
        return $categories;
    }

    /**
     * Get StoreManagerInterface instance
     *
     * @return StoreManagerInterface
     */
    private function _getStoreManager()
    {
        if ($this->_storeManager === null) {
            $this->_storeManager = ObjectManager::getInstance()->get(StoreManagerInterface::class);
        }
        return $this->_storeManager;
    }
}
