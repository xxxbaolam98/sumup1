<?php
namespace Smartosc\Sumup\Model\ResourceModel\Sumup;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Define model & resource model
     */
    const YOUR_TABLE = 'sumup_blog';

    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        $this->_init(
            'Smartosc\Sumup\Model\Blog',
            'Smartosc\Sumup\Model\ResourceModel\Blog'
        );
        parent::__construct(
            $entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource
        );
        $this->storeManager = $storeManager;
    }
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->joinColumn();
    }

    public function joinColumn()
    {
        //tags
        $blogTags = [];
        foreach ($this as $blog) {
            $blogTags[$blog->getId()] = [];
        }

        if (!empty($blogTags)) {
            $select = $this->getConnection()->select()->from(
                ['sumup_blog_tag' => "sumup_blog_tag"]
            )->join(
                ['sumup_tag' => $this->getResource()->getTable('sumup_tag')],
                'sumup_tag.tag_id = sumup_blog_tag.tag_id',
                ['tag_name']
            )->where(
                'sumup_blog_tag.blog_id IN (?)',
                array_keys($blogTags)
            )->where(
                'sumup_tag.tag_id > ?',
                0
            );

            $data = $this->getConnection()->fetchAll($select);
            foreach ($data as $row) {
                $blogTags[$row['blog_id']][] = $row['tag_id'];
            }
        }

        foreach ($this as $blog) {
            if (isset($blogTags[$blog->getId()])) {
                $blog->setData('tag_id', $blogTags[$blog->getId()]);
            }
        }

        //category
        $blogCategory = [];
        foreach ($this as $blog) {
            $blogCategory[$blog->getId()] = [];
        }

        if (!empty($blogCategory)) {
            $select = $this->getConnection()->select()->from(
                ['sumup_blog_category' => "sumup_blog_category"]
            )->join(
                ['sumup_category' => $this->getResource()->getTable('sumup_category')],
                'sumup_category.category_id = sumup_blog_category.category_id',
                ['category_name']
            )->where(
                'sumup_blog_category.blog_id IN (?)',
                array_keys($blogCategory)
            )->where(
                'sumup_category.category_id > ?',
                0
            );

            $data = $this->getConnection()->fetchAll($select);
            foreach ($data as $row) {
                $blogCategory[$row['blog_id']][] = $row['category_id'];
            }
        }

        foreach ($this as $blog) {
            if (isset($blogCategory[$blog->getId()])) {
                $blog->setData('category_id', $blogCategory[$blog->getId()]);
            }
        }

        //products
        $blogProducts = [];
        foreach ($this as $blog) {
            $blogProducts[$blog->getId()] = [];
        }

        if (!empty($blogProducts)) {
            $select = $this->getConnection()->select()->from(
                ['sumup_blog_products' => "sumup_blog_products"]
            )->join(
                ['catalog_product_entity' => $this->getResource()->getTable('catalog_product_entity')],
                'catalog_product_entity.entity_id = sumup_blog_products.product_id')->where(
                'sumup_blog_products.blog_id IN (?)',
                array_keys($blogProducts)
            )->where(
                'catalog_product_entity.entity_id > ?',
                0
            );

            $data = $this->getConnection()->fetchAll($select);
            foreach ($data as $row) {
                $blogProducts[$row['blog_id']][] = $row['product_id'];
            }
        }

        foreach ($this as $blog) {
            if (isset($blogProducts[$blog->getId()])) {
                $blog->setData('products', $blogProducts[$blog->getId()]);
            }
        }

        return $this;
    }

    public function addStatus()
    {
        $this->sumup_blog_status = $this->getTable("sumup_blog_status");
        $this->getSelect()->joinleft(array('sumup_blog_status' =>$this->sumup_blog_status),  'main_table.status_id= sumup_blog_status.status_id');
    }
}
?>
