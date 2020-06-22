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
class Status extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * Column name
     */
    const NAME = 'status';

    /**
     * Data for concatenated website names value.
     */
    private $statusNames = 'status_names';

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

    protected $_statusFactory;

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
        \Smartosc\Sumup\Model\StatusFactory $statusFactory
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->storeManager = $storeManager;
        $this->resourceHelper = $resourceHelper ?: $objectManager->get(Helper::class);
        $this->_statusFactory = $statusFactory;
    }

    /**
     * @inheritdoc
     *
     * @deprecated 101.0.0
     */
    public function prepareDataSource(array $dataSource)
    {
        $statusNames = [];
        $statusList = $this->_statusFactory->create();
        $collection = $statusList->getCollection();
        foreach($collection as $item)
        {
            $item->getData();
            $statusNames[$item['status_id']] = $item['status_name'];
        }

        $countStatus = count($statusNames);
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $status = "";
                for($i = 1; $i <= $countStatus; $i++){
                    if($item[$fieldName] == $i) {
                        $status = $statusNames[$i];
                        break;
                    }
                }
                $item[$fieldName] = $status;
            }
        }

        return $dataSource;
    }


}
