<?php
namespace Smartosc\Brand\Model\ResourceModel\Brand;

use Smartosc\Brand\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{
    const SORT_ORDER_ASC = 'asc';
    const SORT_ORDER_DESC = 'desc';
    /**
     * @var string
     */
    protected $_idFieldName = 'brand_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Smartosc\Brand\Model\Brand', 'Smartosc\Brand\Model\ResourceModel\Brand');
        $this->_map['fields']['brand_id'] = 'main_table.brand_id';
        $this->_map['fields']['store'] = 'store_table.store_id';
    }

    /**
     * Returns pairs identifier - title for unique identifiers
     * and pairs identifier|brand_id - title for non-unique after first
     *
     * @return array
     */
    public function toOptionIdArray()
    {
        $res = [];
        $existingIdentifiers = [];
        foreach ($this as $item) {
            $identifier = $item->getData('url_key');

            $data['value'] = $identifier;
            $data['label'] = $item->getData('title');

            if (in_array($identifier, $existingIdentifiers)) {
                $data['value'] .= '|' . $item->getData('brand_id');
            } else {
                $existingIdentifiers[] = $identifier;
            }

            $res[] = $data;
        }

        return $res;
    }

    /**
     * Set first store flag
     *
     * @param bool $flag
     * @return $this
     */
    public function setFirstStoreFlag($flag = false)
    {
        $this->_previewFlag = $flag;
        return $this;
    }

    /**
     * Add filter by store
     *
     * @param int|array|\Magento\Store\Model\Store $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if (!$this->getFlag('store_filter_added')) {
            $this->performAddStoreFilter($store, $withAdmin);
        }
        return $this;
    }

    /**
     * Perform operations after collection load
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        $this->performAfterLoad('smartosc_brand_store', 'brand_id');
        $this->_previewFlag = false;

        return parent::_afterLoad();
    }

    /**
     * Perform operations before rendering filters
     *
     * @return void
     */
    protected function _renderFiltersBefore()
    {
        $this->joinStoreRelationTable('smartosc_brand_store', 'brand_id');
    }
    /**
     * Add attribute to sort order
     *
     * @param string $attribute
     * @param string $dir
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function addAttributeToSort($attribute, $dir = self::SORT_ORDER_ASC)
    {
        $column = $attribute;
        if ($attribute != "entity_id") {
            $this->getSelect()->order("main_table.{$column} {$dir}");
        }
        return $this;
    }
}
