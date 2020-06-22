<?php
namespace Smartosc\Sumup\Model\ResourceModel\Blog;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'blog_id';
    protected $_resource;
    protected $blogFactory;
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Smartosc\Sumup\Model\Blog', 'Smartosc\Sumup\Model\ResourceModel\Blog');
    }
    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceQuery,
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Smartosc\Sumup\Model\BlogFactory $blogFactory,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $connection,
            $resource
        );
        $this->blogFactory = $blogFactory;
        $this->storeManager = $storeManager;
        $this->_resource = $resourceQuery;
    }
    protected function _initSelect()
    {
        parent::_initSelect();
//        $this->getBlogTag();
//        $this->getBlogCategory();
//        $this->getBlogProduct();
    }

    public function insertBlogCategory($blog_id, $category_ids = [])
    {
        $connection = $this->_resource->getConnection();
        $tableName = "sumup_blog_category";
        foreach ($category_ids as $category_id) {
            $sql = "Insert Into " . $tableName . " (blog_id, category_id) Values (" . $blog_id . " ," . $category_id . ")";
            $connection->query($sql);
        }
    }

    public function insertBlogTag($blog_id, $tag_ids = [])
    {
        $connection = $this->_resource->getConnection();
        $tableName = "sumup_blog_tag";
        foreach ($tag_ids as $tag_id) {
            $sql = "Insert Into " . $tableName . " (blog_id, tag_id) Values (" . $blog_id . " ," . $tag_id . ")";
            $connection->query($sql);
        }
    }

    public function deleteBlogTag($blog_id)
    {
        $connection = $this->_resource->getConnection();
        $tableName = "sumup_blog_tag";
        $sql = "Delete from " . $tableName . " Where  blog_id = " . $blog_id;
        $connection->query($sql);
    }

    public function deleteBlogCategory($blog_id)
    {
        $connection = $this->_resource->getConnection();
        $tableName = "sumup_blog_category";
        $sql = "Delete from " . $tableName . " Where  blog_id = " . $blog_id;
        $connection->query($sql);
    }

    public function deleteBlogProducts($blog_id)
    {
        $connection = $this->_resource->getConnection();
        $tableName = "sumup_blog_products";
        $sql = "Delete from " . $tableName . " Where  blog_id = " . $blog_id;
        $connection->query($sql);
    }

    public function insertBlogProducts($blog_id, $products = [])
    {
        $connection = $this->_resource->getConnection();
        $tableName = "sumup_blog_products";
        foreach ($products as $product) {
            $sql = "Insert Into " . $tableName . " (blog_id, product_id) Values (" . $blog_id . " ," . $product['id'] . ")";
            $connection->query($sql);
        }
    }

    public function getTagCategory($blog_id)
    {
        $blogtagTable = $this->_resource->getTableName('sumup_blog_tag');
        $tagTable = $this->_resource->getTableName('sumup_tag');
        $data = $this->getConnection()->fetchAll('SELECT * FROM ' . $blogtagTable . ' as `blogtag`
            INNER JOIN ' . $tagTable . ' as `tag` ON blogtag.tag_id = tag.tag_id WHERE blogtag.blog_id = ' . $blog_id . ';');

        $tagnames = [];
        foreach ($data as $blogtag) {
            array_push($tagnames, $blogtag['tag_name']);
        }

        $blogcateTable = $this->_resource->getTableName('sumup_blog_category');
        $cateTable = $this->_resource->getTableName('sumup_category');
        $data2 = $this->getConnection()->fetchAll('SELECT * FROM ' . $blogcateTable . ' as `blogcate`
            INNER JOIN ' . $cateTable . ' as `cate` ON blogcate.category_id = cate.category_id WHERE blogcate.blog_id = ' . $blog_id . ';');

        $catenames = [];
        foreach ($data2 as $blogcate) {
            array_push($catenames, $blogcate['category_name']);
        }

        foreach ($this as $blog) {
            if ($blog_id == $blog->getId()) {
                $blog->setData('tag_names', $tagnames);
                $blog->setData('category_names', $catenames);
                return $blog;
            }
        }

        return null;
    }

//    public function getCategories($blog_id)
//    {
//        $table1 = $this->_resource->getTableName('sumup_blog_category');
//        $table2 = $this->_resource->getTableName('sumup_category');
//        $data = $this->getConnection()->fetchAll('SELECT * FROM ' . $table1 . ' as `blogcate`
//            INNER JOIN ' . $table2 . ' as `cate` ON blogcate.category_id = cate.category_id WHERE blogtag.blog_id = ' . $blog_id . ';');
//
//        $catenames = [];
//        foreach ($data as $blogcate) {
//            array_push($catenames, $blogcate['category_name']);
//        }
//        foreach($this as $blog) {
//            if($blog_id == $blog->getId()) {
//                $blog->setData('category_names', $catenames);
//            }
//        }
//    }

    public function getProducts($blog_id)
    {
        $table1 = $this->_resource->getTableName('sumup_blog_products');
        $table2 = $this->_resource->getTableName('catalog_product_entity');
        $data = $this->getConnection()->fetchAll('SELECT * FROM ' . $table1 . ' as `blogproducts`
            INNER JOIN ' . $table2 . ' as `product` ON blogproducts.product_id = product.entity_id WHERE blogproducts.blog_id = ' . $blog_id . ';');

        $product_ids = [];
        foreach ($data as $blogcate) {
            array_push($product_ids, $blogcate['product_id']);
        }
        foreach ($this as $blog) {
            if ($blog_id == $blog->getId()) {
                $blog->setData('products', $product_ids);
                return $blog;
            }
        }
    }
}
