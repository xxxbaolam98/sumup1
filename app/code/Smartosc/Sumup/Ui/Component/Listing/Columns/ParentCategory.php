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
class ParentCategory extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * Column name
     */
    const NAME = 'category';

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

    protected $_categoryFactory;

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
        Helper $resourceHelper = null,
        \Smartosc\Sumup\Model\CategoryFactory $categoryFactory
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->storeManager = $storeManager;
        $this->resourceHelper = $resourceHelper ?: $objectManager->get(Helper::class);
        $this->_categoryFactory = $categoryFactory;
    }

    /**
     * @inheritdoc
     *
     * @deprecated 101.0.0
     */
    public function prepareDataSource(array $dataSource)
    {
        $categoryNames = [];
        $categoryList = $this->_categoryFactory->create();
        $collection = $categoryList->getCollection();
        foreach($collection as $item)
        {
            $item->getData();
            $categoryNames[$item['category_id']] = $item['category_name'];
        }

        $countCategory = count($categoryNames);
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $category = "";
                for($i = 1; $i <= $countCategory; $i++){
                    if($item[$fieldName] == $i) {
                        $category = $categoryNames[$i];
                        break;
                    }
                }
                $item[$fieldName] = $category;
            }
        }

        return $dataSource;
    }


}
