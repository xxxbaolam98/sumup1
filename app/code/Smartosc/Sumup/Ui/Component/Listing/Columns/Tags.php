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
class Tags extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * Column name
     */
    const NAME = 'tags';

    /**
     * Data for concatenated website names value.
     */
    private $tagNames = 'tag_names';

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
        $tagNames = [];
        foreach ($this->getData('options') as $tag) {
            $tag_arr = $tag->toArray();
            $tagNames[$tag_arr['tag_id']] = $tag_arr['tag_name'];
        }

        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $tags = [];
                foreach ($item[$fieldName] as $tagId) {
                    if (!isset($tagNames[$tagId])) {
                        continue;
                    }
                    $tags[] = $tagNames[$tagId];
                }
                $item[$fieldName] = implode(', ', $tags);
            }
        }

        return $dataSource;
    }


}
