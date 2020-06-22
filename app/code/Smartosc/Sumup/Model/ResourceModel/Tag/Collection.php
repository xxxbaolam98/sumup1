<?php
namespace Smartosc\Sumup\Model\ResourceModel\Tag;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'tag_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Smartosc\Sumup\Model\Tag', 'Smartosc\Sumup\Model\ResourceModel\Tag');
    }

}
