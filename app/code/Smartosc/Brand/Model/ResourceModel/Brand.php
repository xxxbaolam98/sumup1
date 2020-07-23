<?php
namespace Smartosc\Brand\Model\ResourceModel;

use Magento\Catalog\Model\ProductFactory;

class Brand extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Store model
     *
     * @var \Magento\Store\Model\Store
     */
    protected $_store = null;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * Store manager
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\Stdlib\Datetime
     */
    protected $dateTime;

    protected $productFactory;

    protected $collectionFactory;

    protected $brandFactory;

    /**
     * Construct
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Stdlib\DateTime $dateTime
     * @param ProductFactory $productFactory
     * @param string $connectionName
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        ProductFactory $productFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        \Smartosc\Brand\Model\BrandFactory $brandFactory,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->brandFactory = $brandFactory;
        $this->productFactory = $productFactory;
        $this->collectionFactory = $collectionFactory;
        $this->_date = $date;
        $this->_storeManager = $storeManager;
        $this->dateTime = $dateTime;
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('smartosc_brand', 'brand_id');
    }

    /**
     *  Check whether brand url key is numeric
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return bool
     */
    protected function isNumericBrandUrlKey(\Magento\Framework\Model\AbstractModel $object)
    {
        return preg_match('/^[0-9]+$/', $object->getData('url_key'));
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param \Magento\Cms\Model\Page $object
     * @return \Magento\Framework\DB\Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {
            $storeIds = [\Magento\Store\Model\Store::DEFAULT_STORE_ID, (int)$object->getStoreId()];
            $select->join(
                ['smartosc_brand_store' => $this->getTable('smartosc_brand_store')],
                $this->getMainTable() . '.brand_id = smartosc_brand_store.brand_id',
                []
            )->where(
                'status = ?',
                1
            )->where(
                'smartosc_brand_store.store_id IN (?)',
                $storeIds
            )->order(
                'smartosc_brand_store.store_id DESC'
            )->limit(
                1
            );
        }

        return $select;
    }

    /**
     * Retrieve load select with filter by identifier, store and activity
     *
     * @param string $identifier
     * @param int|array $store
     * @param int $isActive
     * @return \Magento\Framework\DB\Select
     */
    protected function _getLoadByIdentifierSelect($identifier, $store, $isActive = null)
    {
        $select = $this->getConnection()->select()->from(
            ['cp' => $this->getMainTable()]
        )->join(
            ['cps' => $this->getTable('smartosc_brand_store')],
            'cp.brand_id = cps.brand_id',
            []
        )->where(
            'cp.identifier = ?',
            $identifier
        )->where(
            'cps.store_id IN (?)',
            $store
        );

        if (!is_null($isActive)) {
            $select->where('cp.status = ?', $isActive);
        }

        return $select;
    }

    /**
     * Check if brand url key exist for specific store
     * return brand id if brand exists
     *
     * @param string $url_key
     * @param int $storeId
     * @return int
     */
    public function checkIdentifier($url_key, $storeId)
    {
        $stores = [\Magento\Store\Model\Store::DEFAULT_STORE_ID, $storeId];
        $select = $this->_getLoadByIdentifierSelect($url_key, $stores, 1);
        $select->reset(\Magento\Framework\DB\Select::COLUMNS)->columns('cp.brand_id')->order('cps.store_id DESC')->limit(1);

        return $this->getConnection()->fetchOne($select);
    }

    /**
     * Process brand data before deleting
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _beforeDelete(\Magento\Framework\Model\AbstractModel $object)
    {
        $condition = ['brand_id = ?' => (int)$object->getId()];
        $this->getConnection()->delete($this->getTable('smartosc_brand_store'), $condition);

        $condition = ['brand_id = ?' => (int)$object->getId()];
        $this->getConnection()->delete($this->getTable('smartosc_brand_product'), $condition);

        $this->removeBrandFromProduct((int)$object->getId());

        return parent::_beforeDelete($object);
    }

    public function deleteBrandProduct($product_id)
    {
        $condition = ['product_id = ?' => $product_id];
        $this->getConnection()->delete($this->getTable('smartosc_brand_product'), $condition);
    }

    public function removeBrandFromProduct($brand_id)
    {
        $brand = $this->brandFactory->create();
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

    public function saveBrandProduct($brand_arr, $product_id)
    {
        $table = $this->getTable('smartosc_brand_product');
        if (!empty($brand_arr)) {
            foreach ($brand_arr as $brand_id) {
                $data[] = [
                    'brand_id' => $brand_id,
                    'product_id' => $product_id,
                    'position' => $product_id
                ];
            }
            $this->getConnection()->insertMultiple($table, $data);
        }
    }

    /**
     * Assign brand to store views
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $newStores = (array)$object->getStores();
        $table = $this->getTable('smartosc_brand_store');

        $where = ['brand_id = ?' => (int)$object->getId()];
        $this->getConnection()->delete($table, $where);

        $data = [];
        foreach ($newStores as $storeId) {
            $data[] = ['brand_id' => (int)$object->getId(), 'store_id' => (int)$storeId];
        }
        $this->getConnection()->insertMultiple($table, $data);


        // Posts Related
        if (null !== ($object->getData('products'))) {
            $table = $this->getTable('smartosc_brand_product');
            $where = ['brand_id = ?' => (int)$object->getId()];
            $this->getConnection()->delete($table, $where);

            if ($quetionProducts = $object->getData('products')) {
                $where = ['brand_id = ?' => (int)$object->getId()];
                $this->getConnection()->delete($table, $where);
                $data = [];
                foreach ($quetionProducts as $k => $_post) {
                    $data[] = [
                        'brand_id' => (int)$object->getId(),
                        'product_id' => $_post['id'],
                        'position' => $_post['position']
                    ];
                }
                $this->getConnection()->insertMultiple($table, $data);
            }
        }

        return parent::_afterSave($object);
    }

    public function saveProduct(\Magento\Framework\Model\AbstractModel $object, $product_id = 0)
    {
        if ($object->getId() && $product_id) {
            $table = $this->getTable('smartosc_brand_product');

            $select = $this->getConnection()->select()->from(
                ['cp' => $table]
            )->where(
                'cp.brand_id = ?',
                (int)$object->getId()
            )->where(
                'cp.product_id = (?)',
                (int)$product_id
            )->limit(1);

            $row_product = $this->getConnection()->fetchAll($select);

            if (!$row_product) { // check if not exists product, then insert it into database
                $data = [];
                $data[] = [
                    'brand_id' => (int)$object->getId(),
                    'product_id' => (int)$product_id,
                    'position' => 0
                ];

                $this->getConnection()->insertMultiple($table, $data);
            }
            return true;
        }
        return false;
    }

    public function deleteBrandsByProduct($product_id = 0)
    {
        if ($product_id) {
            $condition = ['product_id = ?' => (int)$product_id];
            $this->getConnection()->delete($this->getTable('smartosc_brand_product'), $condition);
            return true;
        }
        return false;
    }

    public function getBrandIdByName($brand_name = '')
    {
        if ($brand_name) {
            $brand_id = null;
            $table = $this->getTable('smartosc_brand');

            $select = $this->getConnection()->select()->from(
                ['cp' => $table]
            )->where(
                'cp.name = ?',
                $brand_name
            )->limit(1);

            $row_brand = $this->getConnection()->fetchAll($select);
            if ($row_brand) { // check if have brand record

                $brand_id = isset($row_brand[0]['brand_id']) ? (int)$row_brand[0]['brand_id'] : null;
            }
            return $brand_id;
        }
        return null;
    }

    /**
     * Load an object using 'url_key' field if there's no field specified and value is not numeric
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @param mixed $value
     * @param string $field
     * @return $this
     */
    public function load(\Magento\Framework\Model\AbstractModel $object, $value, $field = null)
    {
        if (!is_numeric($value) && is_null($field)) {
            $field = 'url_key';
        }

        return parent::load($object, $value, $field);
    }

    /**
     * Perform operations after object load
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());
            $object->setData('store_id', $stores);
        }

        if ($id = $object->getId()) {
            $connection = $this->getConnection();
            $select = $connection->select()
                ->from($this->getTable('smartosc_brand_product'))
                ->where(
                    'brand_id = ' . (int)$id
                );
            $products = $connection->fetchAll($select);
            $object->setData('products', $products);
        }

        return parent::_afterLoad($object);
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $brandId
     * @return array
     */
    public function lookupStoreIds($brandId)
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from(
            $this->getTable('smartosc_brand_store'),
            'store_id'
        )
            ->where(
                'brand_id = ?',
                (int)$brandId
            );
        return $connection->fetchCol($select);
    }

    public function checkUrlExits(\Magento\Framework\Model\AbstractModel $object)
    {
        $stores = $object->getStores();
        $connection = $this->getConnection();
        $select = $connection->select()->from(
            $this->getTable('smartosc_brand'),
            'brand_id'
        )
            ->where(
                'url_key = ?',
                $object->getUrlKey()
            )
            ->where(
                'brand_id != ?',
                $object->getId()
            );

        $brandIds = $connection->fetchCol($select);
        if (count($brandIds)>0 && is_array($stores)) {
            if (in_array('0', $stores)) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('URL key for specified store already exists.')
                );
            }
            $stores[] = '0';
            $select = $connection->select()->from(
                $this->getTable('smartosc_brand_store'),
                'brand_id'
            )
                ->where(
                    'brand_id IN (?)',
                    $brandIds
                )
                ->where(
                    'store_id IN (?)',
                    $stores
                );
            $result = $connection->fetchCol($select);
            if (count($result)>0) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('URL key for specified store already exists.')
                );
            }
        }
        return $this;
    }
}
