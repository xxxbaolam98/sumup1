<?php
namespace Smartosc\Brand\Block\Brand;

class BrandDetail extends \Magento\Framework\View\Element\Template
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * Catalog layer
     *
     * @var \Magento\Catalog\Model\Layer
     */
    protected $_catalogLayer;

    /**
     * @var \Magento\Catalog\Helper\Category
     */
    protected $_brandHelper;

    protected $brandFactory;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\Layer\Resolver            $layerResolver
     * @param \Magento\Framework\Registry                      $registry
     * @param \Smartosc\Brand\Helper\Data                           $brandHelper
     * @param array                                            $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Framework\Registry $registry,
        \Smartosc\Brand\Helper\Data $brandHelper,
        \Smartosc\Brand\Model\BrandFactory $brandFactory,
        array $data = []
    ) {
        $this->brandFactory = $brandFactory;
        $this->_brandHelper = $brandHelper;
        $this->_catalogLayer = $layerResolver->get();
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    public function getCurrentBrand()
    {
        $brand = $this->brandFactory->create();
        $brand_id = $this->getRequest()->getParam('brand_id');
        $brand->load($brand_id);

        return $brand;
    }

    /**
     * @return string
     */
    public function getProductListHtml()
    {
        return $this->getChildHtml('product_list');
    }

    /**
     * Prepare global layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->pageConfig->addBodyClass('brand-branddetail');
        $brand_id = $this->getRequest()->getParam('brand_id');
        $brand = $this->brandFactory->create();
        $brand->load($brand_id);
        $this->pageConfig->getTitle()->set($brand->getName());

        return parent::_prepareLayout();
    }

}
