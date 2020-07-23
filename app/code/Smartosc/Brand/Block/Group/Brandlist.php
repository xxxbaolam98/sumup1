<?php

namespace Smartosc\Brand\Block;

class Brandlist extends \Magento\Framework\View\Element\Template
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

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry                      $registry
     * @param \Smartosc\Brand\Helper\Data                           $brandHelper
     * @param \Smartosc\Brand\Model\Brand                           $brand
     * @param \Magento\Store\Model\StoreManagerInterface       $storeManager
     * @param array                                            $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Smartosc\Brand\Helper\Data $brandHelper,
        \Smartosc\Brand\Model\BrandFactory $brand,
        array $data = []
    ) {
        $this->_brand = $brand;
        $this->_coreRegistry = $registry;
        $this->_brandHelper = $brandHelper;
        parent::__construct($context, $data);
    }

    /**
     * Prepare breadcrumbs
     *
     * @param \Magento\Cms\Model\Page $brand
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return void
     */

    /**
     * Set brand collection
     * @param \Smartosc\Brand\Model\Brand
     */
    public function setCollection($collection)
    {
        $this->_collection = $collection;
        return $this->_collection;
    }

    /**
     * Retrive brand collection
     * @param \Smartosc\Brand\Model\Brand
     */
    public function getBrandCollection()
    {
        $first_char = $this->getRequest()->getParam('letter');
        $brand = $this->_brand->create();
        $collection = $brand->getCollection();
        $collection->getSelect()->reset(\Magento\Framework\DB\Select::ORDER);
        $collection->setOrder('position', 'ASC');
        $collection->addFieldToFilter('status', 1);
        $collection->addFieldToFilter('name', ['like' => $first_char . '%']);
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
}
