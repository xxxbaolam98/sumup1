<?php
namespace Smartosc\Demo\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;

    protected $_helloworldblogFactory;

    protected $_productFactory;

    protected $productRepository;
    protected $registry;


    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Smartosc\Demo\Model\HelloworldBlogFactory $helloworldblogFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\Registry $registry

    )
    {
        $this->_pageFactory = $pageFactory;
        $this->_helloworldblogFactory = $helloworldblogFactory;
        $this->_productFactory = $productFactory;
        $this->productRepository = $productRepository;
        $this->registry = $registry;

        return parent::__construct($context);
    }

    public function execute()
    {
        $blog = $this->_helloworldblogFactory->create();
        $collection = $blog->getCollection();
        foreach($collection as $item){
            echo "<pre>";
            print_r($item->getData());
            echo "</pre>";
        }


        //delete product by sku
//        $this->registry->register('isSecureArea', true);
//        // using sku
//        $this->productRepository->deleteById('abc123');



        // create product
//        $product = $this->_productFactory->create();
//        $product->setSku("abc123");
//        $product->setName('Sample Simple Product'); // Name of Product
//        $product->setAttributeSetId(4); // Attribute set id
//        $product->setStatus(1); // Status on product enabled/ disabled 1/0
//        $product->setVisibility(4); // visibilty of product (catalog / search / catalog, search / Not visible individually)
//        $product->setTypeId('simple'); // type of product (simple/virtual/downloadable/configurable)
//        $product->setPrice(100); // price of product
//        $product->setWeight(10); // weight of product
//
//        $product->setStockData(
//            array(
//                'use_config_manage_stock' => 0,
//                'manage_stock' => 1,
//                'is_in_stock' => 1,
//                'qty' => 100
//            )
//        );
//        $product->save();

        $product = $this->_productFactory->create();

       // select product by sku
        $productCollection1 = $product->getCollection();
        $productCollection1->addAttributeToFilter(
            [
                ['attribute' => 'sku', 'like' => '%aaaa%']
            ]);
        //update price for specific sku
        foreach ($productCollection1 as $latest_product) {
            $prosku = $latest_product->getId();
            $product->load($prosku);
            $product->setPrice(100);
            $product->save();

            echo "<br>";
        }

        $productCollection = $product->getCollection();
        $productCollection->setOrder('entity_id','DESC');
        $productCollection->setPageSize(10);
        $productCollection->addAttributeToSelect('price');


        foreach ($productCollection as $latest_product) {
            print_r($latest_product->getData());
            echo "<br>";
        }
        exit;
        return $this->_pageFactory->create();
    }
}
