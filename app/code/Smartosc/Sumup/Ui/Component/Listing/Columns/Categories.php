<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Smartosc\Sumup\Ui\Component\Listing\Columns;

use Magento\Framework\DB\Helper;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Websites listing column component.
 *
 * @api
 * @since 100.0.2
 */
class Categories extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * Column name
     */
    const NAME = 'categories';

    /**
     * Data for concatenated website names value.
     */
    private $categoryNames = 'category_names';

    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\DB\Helper
     */
    private $resourceHelper;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param StoreManagerInterface $storeManager
     * @param array $components
     * @param array $data
     * @param Helper $resourceHelper
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        StoreManagerInterface $storeManager,
        array $components = [],
        array $data = [],
        Helper $resourceHelper = null
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->storeManager = $storeManager;
        $this->resourceHelper = $resourceHelper ?: $objectManager->get(Helper::class);
    }

    /**
     * @inheritdoc
     *
     * @deprecated 101.0.0
     */
    public function prepareDataSource(array $dataSource)
    {
        $categoryNames = [];
        foreach ($this->getData('options') as $category) {
            $cate_arr = $category->toArray();
            $categoryNames[$cate_arr['category_id']] = $cate_arr['category_name'];
        }

        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $categories = [];
                foreach ($item[$fieldName] as $cateId) {
                    if (!isset($categoryNames[$cateId])) {
                        continue;
                    }
                    $categories[] = $categoryNames[$cateId];
                }
                $item[$fieldName] = implode(', ', $categories);
            }
        }

        return $dataSource;
    }


}
