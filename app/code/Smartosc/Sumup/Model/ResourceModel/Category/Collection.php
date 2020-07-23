<?php
namespace Smartosc\Sumup\Model\ResourceModel\Category;
use Magento\Framework\App\ObjectManager;
use Smartosc\Sumup\Model\Category;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'category_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Smartosc\Sumup\Model\Category', 'Smartosc\Sumup\Model\ResourceModel\Category');
    }

    /**
     * @var bool
     */
    protected $fromRoot = true;


    protected function _initSelect()
    {
        parent::_initSelect();

        $this->getSelect()->order('category_id');

        return $this;
    }

}
