<?php

namespace Smartosc\Brand\Block\Group;

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

    protected $_storeManager;

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
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->_storeManager = $storeManager;
        $this->_brand = $brand;
        $this->_coreRegistry = $registry;
        $this->_brandHelper = $brandHelper;
        parent::__construct($context, $data);
    }

    public function getBrandCollection()
    {
        $storeid =  $this->_storeManager->getStore()->getId();
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = 5;
        $first_char = $this->getFirstChar();
        $brand = $this->_brand->create();
        $collection = $brand->getCollection();
        $collection->getSelect()->reset(\Magento\Framework\DB\Select::ORDER);
        $collection->setOrder('position', 'ASC');
        $collection->addFieldToFilter('store_id', ['in' => [0,$storeid]]);
        $collection->addFieldToFilter('status', 1);
        $collection->addFieldToFilter('name', ['like' => $first_char . '%']);
        $collection->setPageSize($pageSize);
        $collection->setCurPage($page);
        $this->_collection = $collection;
        return $collection;
    }

    public function getFirstChar()
    {
        return $this->getRequest()->getParam('group');
    }

    /**
     * Prepare global layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->pageConfig->addBodyClass('brand-groupbrandlist');
        $first_char = $this->getFirstChar();
        $this->pageConfig->getTitle()->set('Brand Group ' . $first_char);
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
}
