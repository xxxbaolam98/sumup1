<?php

namespace Smartosc\Sumup\Model;

use Smartosc\Sumup\Api\Data;
use Smartosc\Sumup\Api\CategoryRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Smartosc\Sumup\Model\ResourceModel\Category as ResourceCategory;
use Smartosc\Sumup\Model\ResourceModel\Category\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    protected $resource;

    protected $categoryFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataCategoryFactory;

    private $storeManager;

    private $collectionFactory;

    public function __construct(
        ResourceCategory $resource,
        CategoryFactory $categoryFactory,
        Data\CategoryInterfaceFactory $dataCategoryFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionFactory $collectionFactory
    ) {
        $this->resource = $resource;
        $this->categoryFactory = $categoryFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataCategoryFactory = $dataCategoryFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionFactory = $collectionFactory;

    }

    /**
     * {@inheritdoc}
     */
    public function getCollection()
    {
        return $this->collectionFactory->create();
    }

    public function save(\Smartosc\Sumup\Api\Data\CategoryInterface $category)
    {

        try {
            $this->resource->save($category);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the news: %1', $exception->getMessage()),
                $exception
            );
        }
        return $category;
    }

    public function getById($categoryId)
    {
        $category = $this->categoryFactory->create();
        $category->load($categoryId);
        if (!$category->getId()) {
            throw new NoSuchEntityException(__('category with id "%1" does not exist.', $categoryId));
        }
        return $category;
    }

    public function delete(\Smartosc\Sumup\Api\Data\CategoryInterface $category)
    {
        try {
            $this->resource->delete($category);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the category: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    public function deleteById($categoryId)
    {
        return $this->delete($this->getById($categoryId));
    }
}
?>
