<?php

namespace Smartosc\Sumup\Model\Blog;
use Magento\Framework\Option\ArrayInterface;
use Smartosc\Sumup\Api\Data\CategoryInterface;
use Smartosc\Sumup\Api\CategoryRepositoryInterface;
class CategoryTree implements ArrayInterface
{
    private $categoryRepository;

    public function __construct(
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        $collection = $this->categoryRepository->getCollection();
        $rootId     = $collection->getRootId();

        return [$this->getOptions($rootId)];
    }

    /**
     * @param int $parentId
     *
     * @return array
     */
    private function getOptions($parentId)
    {
        $category = $this->categoryRepository->getById($parentId);

        $data = [
            'label' => $category->getName(),
            'value' => $category->getId(),
        ];

        $collection = $this->categoryRepository->getCollection()
            ->addFieldToFilter(CategoryInterface::PARENT_ID, $category->getId())
            ->setOrder('category_id', 'asc');

        /** @var CategoryInterface $item */
        foreach ($collection as $item) {
            $data['optgroup'][] = $this->getOptions($item->getId());
        }
        return $data;
    }
}
