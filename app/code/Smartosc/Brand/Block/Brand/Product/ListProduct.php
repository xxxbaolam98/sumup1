<?php
namespace Smartosc\Brand\Block\Brand\Product;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;

class ListProduct extends \Magento\Catalog\Block\Product\ListProduct
{
    /**
     * Default toolbar block name
     *
     * @var string
     */
    protected $_defaultToolbarBlock = 'Magento\Catalog\Block\Product\ProductList\Toolbar';

    /**
     * Product Collection
     *
     * @var AbstractCollection
     */
    protected $_productCollection;

    /**
     * Catalog layer
     *
     * @var \Magento\Catalog\Model\Layer
     */
    protected $_catalogLayer;

    /**
     * @var \Magento\Framework\Data\Helper\PostHelper
     */
    protected $_postDataHelper;

    /**
     * @var \Magento\Framework\Url\Helper\Data
     */
    protected $urlHelper;

    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * Catalog product visibility
     *
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_catalogProductVisibility;

    protected $_productCollectionFactory;

    protected $brandFactory;

    protected $_storeManager;

    /**
     * @param Context $context
     * @param \Magento\Framework\Data\Helper\PostHelper $postDataHelper
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param CategoryRepositoryInterface $categoryRepository
     * @param \Magento\Framework\Url\Helper\Data $urlHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Smartosc\Brand\Model\BrandFactory $brandFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->_storeManager = $storeManager;
        $this->brandFactory = $brandFactory;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        $this->_catalogLayer = $layerResolver->get();
        $this->_postDataHelper = $postDataHelper;
        $this->categoryRepository = $categoryRepository;
        $this->urlHelper = $urlHelper;
        parent::__construct($context, $postDataHelper, $layerResolver, $categoryRepository, $urlHelper);
    }

    protected function _getProductCollection()
    {
        $storeid =  $this->_storeManager->getStore()->getId();
        $brand_id = $this->getRequest()->getParam('brand_id');
        $brand = $this->brandFactory->create();
        $brand->load($brand_id);
        $products = $brand->getData('products');
        $productIds = [];
        foreach ($products as $k => $v) {
            $productIds[] = $v['product_id'];
        }

        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('product_list_limit')) ? $this->getRequest()->getParam('product_list_limit') : 12;
        $orderby = ($this->getRequest()->getParam('product_list_order')) ? $this->getRequest()->getParam('product_list_order') : 'position';
        $collection = $this->_productCollectionFactory->create();
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds())->addAttributeToSelect('*')->addAttributeToFilter('entity_id', ['in'=>$productIds]);
        $collection->addFieldToFilter('status', ['eq' => 1]);
        $collection->addStoreFilter($storeid);
        $collection->setPageSize($pageSize);
        $collection->setCurPage($page);
        $collection->setOrder($orderby, 'ASC');
        $this->_productCollection = $collection;
        return $this->_productCollection;
    }
}
