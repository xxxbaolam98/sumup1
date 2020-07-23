<?php
namespace Smartosc\Brand\Model\ResourceModel\Sumup;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Define model & resource model
     */
    const YOUR_TABLE = 'smartosc_brand';

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
            'Smartosc\Brand\Model\Brand',
            'Smartosc\Brand\Model\ResourceModel\Brand'
        );
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $connection,
            $resource
        );
    }
   

    public function joinColumn()
    {
        //products
        $brandProducts = [];
        foreach ($this as $brand) {
            $brandProducts[$brand->getId()] = [];
        }

        if (!empty($brandProducts)) {
            $select = $this->getConnection()->select()->from(
                ['smartosc_brand_product' => "smartosc_brand_product"]
            )->join(
                ['catalog_product_entity' => $this->getResource()->getTable('catalog_product_entity')],
                'catalog_product_entity.entity_id = smartosc_brand_product.product_id'
            )->where(
                'smartosc_brand_product.brand_id IN (?)',
                array_keys($brandProducts)
            )->where(
                'catalog_product_entity.entity_id > ?',
                0
            );

            $data = $this->getConnection()->fetchAll($select);
            foreach ($data as $row) {
                $brandProducts[$row['brand_id']][] = $row['product_id'];
            }
        }

        foreach ($this as $brand) {
            if (isset($brandProducts[$brand->getId()])) {
                $brand->setData('products', $brandProducts[$brand->getId()]);
            }
        }

        //store
        $brandStores = [];
        foreach ($this as $brand) {
            $brandStores[$brand->getId()] = [];
        }

        if (!empty($brandStores)) {
            $select = $this->getConnection()->select()->from(
                ['smartosc_brand_store' => "smartosc_brand_store"]
            )->where(
                'smartosc_brand_store.brand_id IN (?)',
                array_keys($brandStores)
            );

            $data = $this->getConnection()->fetchAll($select);
            foreach ($data as $row) {
                $brandStores[$row['brand_id']][] = $row['store_id'];
            }
        }

        foreach ($this as $brand) {
            if (isset($brandStores[$brand->getId()])) {
                $brand->setData('store_ids', $brandStores[$brand->getId()]);
            }
        }

        return $this;
    }
}
?>

