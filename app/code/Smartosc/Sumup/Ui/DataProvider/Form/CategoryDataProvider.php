<?php
namespace Smartosc\Sumup\Ui\DataProvider\Form;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;

class CategoryDataProvider extends \Magento\Ui\DataProvider\ModifierPoolDataProvider
{
    protected $_loadedData;

    protected $dataPersistor;

    protected $collection;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Smartosc\Sumup\Model\ResourceModel\CategorySumup\Grid\CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        PoolInterface $pool = null,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $category) {
            $cate_arr = $category->getData();
            $this->_loadedData[$cate_arr['category_id']] = $category->getData();
        }

        $data = $this->dataPersistor->get('category');
        if (!empty($data)) {
            $category = $this->collection->getNewEmptyItem();
            $category->setData($data);
            $this->_loadedData[$category->getId()] = $category->getData();
            $this->dataPersistor->clear('category');
        }
        return $this->_loadedData;
    }

}
