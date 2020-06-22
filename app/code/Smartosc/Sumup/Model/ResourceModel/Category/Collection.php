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


    /**
     * @return $this
     */
    public function addRootFilter()
    {
        $this->addFieldToFilter('parent_id', 0);

        return $this;
    }

    /**
     * @return $this
     */
    public function excludeRoot()
    {
        $this->fromRoot = false;

        return $this->addFieldToFilter('category_id', ['neq' => $this->getRootId()]);
    }

    /**
     * @return int
     */
    public function getRootId()
    {
        $objectManager = ObjectManager::getInstance();
        /** @var \Smartosc\Sumup\Helper\Category $helper */
        $helper = $objectManager->get('\Smartosc\Sumup\Helper\Category');

        return $helper->getRootCategory()->getId();
    }

    /**
     * @param int|null $parentId
     *
     * @return Category[]
     */
    public function getTree($parentId = null)
    {
        $list = [];

        if ($parentId == null) {
            $parentId = $this->fromRoot ? 0 : $this->getRootId();
        }

        $collection = clone $this;
        $collection->addFieldToFilter('parent_id', $parentId)
            ->setOrder('position', 'asc');

        foreach ($collection as $item) {
            $list[$item->getId()] = $item;
            if ($item->getChildrenCount()) {
                $items = $this->getTree($item->getId());
                foreach ($items as $child) {
                    $list[$child->getId()] = $child;
                }
            }
        }

        return $list;
    }

    protected function _initSelect()
    {
        parent::_initSelect();

        $this->getSelect()->order('category_id');

        return $this;
    }

}
