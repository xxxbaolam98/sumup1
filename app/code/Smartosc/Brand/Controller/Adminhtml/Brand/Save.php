<?php

namespace Smartosc\Brand\Controller\Adminhtml\Brand;

class Save extends \Magento\Backend\App\Action
{
    protected $_pageFactory;
    protected $_brandFactory;
    protected $_messageManager;
    protected $productFactory;
    protected $collectionFactory;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Smartosc\Brand\Model\BrandFactory $brandFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory
    ) {
        parent::__construct($context);
        $this->_pageFactory = $pageFactory;
        $this->_brandFactory = $brandFactory;
        $this->_messageManager = $messageManager;
        $this->productFactory = $productFactory;
        $this->collectionFactory = $collectionFactory;
    }

    public function execute()
    {
        $arr = $this->getRequest()->getParams();

        $resultRedirect = $this->resultRedirectFactory->create();
        $brand_id = 0;
        if (array_key_exists('brand_id', $arr) && !empty($arr['brand_id'])) {
            $brand_id = $arr['brand_id'];
        }

        if ($this->validation($arr) == true) {
            $name = $arr['name'];
            $des = "";
            $status = $arr['status'];
            $feature = $arr['is_feature'];
            $stores = $arr['store_ids'];
            $country = $arr['country_id'];
            $thumbnail = "";
            $url_key = $arr['name'];
            $image = "";
            $products = "";
            if (array_key_exists('links', $arr)) {
                $products = $arr['links']['products'];
            }
            if (array_key_exists('url_key', $arr) && $arr['url_key'] != null && trim($arr['url_key']) != '') {
                $url_key = trim($arr['url_key'], '/. ');
            }
            if (array_key_exists('description', $arr)) {
                $des = $arr['description'];
            }
            if (array_key_exists('thumbnail', $arr) && is_array($arr['thumbnail'])) {
                $thumbnail = $arr['thumbnail'][0]['name'];
            }
            if (array_key_exists('image', $arr) && is_array($arr['image'])) {
                $image = $arr['image'][0]['name'];
            }
            $brand = $this->_brandFactory->create();
            $collection = $brand->getCollection();
            if (array_key_exists('brand_id', $arr) && !empty($arr['brand_id'])) {
                $brand->load($arr['brand_id']);
                $this->removeBrandFromProduct($arr['brand_id']);
            }
            $brand->setData("name", $name);
            $brand->setData("description", $des);
            $brand->setData("store_ids", $stores);
            $brand->setData("country_id", $country);
            $brand->setData("url_key", $url_key);
            $brand->setData("status", $status);
            $brand->setData("thumbnail", $thumbnail);
            $brand->setData("image", $image);
            $brand->setData("products", $products);
            $brand->setData("is_feature", $feature);
            $brand->save();
            $brand_id = $brand->getId();
            $this->removeBrandFromProduct($brand_id);
            if ($brand->getData('products') != "") {
                $pros = $brand->getData('products');
                foreach ($pros as $pro) {
                    $this->addBrandToProduct($brand_id, $pro['id']);
                }
            }
            $this->_messageManager->addSuccessMessage('Save Successfully');
            $resultRedirect->setPath('brand/brand/edit', ["brand_id" => $brand_id]);
        } else {
            if (array_key_exists('brand_id', $arr)) {
                $resultRedirect->setPath('brand/brand/edit', ["brand_id" => $brand_id]);
            } else {
                $resultRedirect->setPath('brand/brand/edit');
            }
        }
        return $resultRedirect;
    }

    public function validation($arr)
    {
        if (!array_key_exists('name', $arr) || empty($arr['name']) || !array_key_exists('status', $arr) || !array_key_exists('country_id', $arr) || empty($arr['country_id'])
             || !array_key_exists('is_feature', $arr)) {
            $this->_messageManager->addErrorMessage('Please Input Required Fields.');
            return false;
        }
        $brand = $this->_brandFactory->create();
        $collection = $brand->getCollection();
        $collection->addFieldToFilter('name', ['eq'=> $arr['name']]);
        if (array_key_exists('brand_id', $arr) || $arr['brand_id'] != null) {
            $collection->addFieldToFilter('brand_id', ['neq'=> $arr['brand_id']]);
        }
        if ($collection->getSize() > 0) {
            $this->_messageManager->addErrorMessage('Name have already exists.');
            return false;
        }
        $brand2 = $this->_brandFactory->create();
        $collection2 = $brand2->getCollection();
        $collection2->addFieldToFilter('url_key', ['eq'=> $arr['url_key']]);
        if (array_key_exists('brand_id', $arr) || $arr['brand_id'] != null) {
            $collection2->addFieldToFilter('brand_id', ['neq'=> $arr['brand_id']]);
        }
        if ($collection2->getSize() > 0) {
            $this->_messageManager->addErrorMessage('Url Key have already exists.');
            return false;
        }

        return true;
    }

    public function addBrandToProduct($brand_id, $product_id)
    {
        $product = $this->productFactory->create();
        $speproduct = $product->load($product_id);
        $product_brand = $speproduct->getData('product_brand');
        if ($product_brand == null) {
            $speproduct->setCustomAttribute('product_brand', $brand_id);
        } else {
            $brands = explode(',', $product_brand);
            array_push($brands, $brand_id);
            $brand_str = implode(",", $brands);
            $speproduct->setCustomAttribute('product_brand', $brand_str);
        }
        $speproduct->save();
    }

    public function removeBrandFromProduct($brand_id)
    {
        $brand = $this->_brandFactory->create();
        $loadBrand = $brand->load($brand_id);
        $products = $loadBrand->getData('products');
        $product_ids = [];
        foreach ($products as $product) {
            array_push($product_ids, $product['product_id']);
        }
        $pro = $this->collectionFactory->create();
        $pro->addAttributeToSelect('*');
        $pro->addFieldToFilter('entity_id', ['in' => $product_ids]);
        foreach ($pro as $product) {
            if (array_key_exists('product_brand', $product->getData())) {
                $brands_arr = explode(",", $product->getData('product_brand'));
                if (($key = array_search($brand_id, $brands_arr)) !== false) {
                    unset($brands_arr[$key]);
                    $brands = implode(",", $brands_arr);
                    $product->setCustomAttribute('product_brand', $brands);
                    $product->save();
                }
            }
        }
    }
}
