<?php
namespace Smartosc\Sumup\Model\ResourceModel\Status;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'status_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Smartosc\Sumup\Model\Status', 'Smartosc\Sumup\Model\ResourceModel\Status');
    }

}
