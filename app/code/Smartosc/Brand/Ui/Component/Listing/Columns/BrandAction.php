<?php

namespace Smartosc\Brand\Ui\Component\Listing\Columns;

use Magento\Cms\Block\Adminhtml\Page\Grid\Renderer\Action\UrlBuilder;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

class BrandAction extends \Magento\Ui\Component\Listing\Columns\Column
{
    /** Url path */
    const POST_URL_PATH_EDIT = 'brand/brand/edit';
    const POST_URL_PATH_DELETE = 'brand/brand/delete';

    /**
     * @var UrlBuilder
     */
    protected $actionUrlBuilder;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var string
     */
    private $editUrl;
    private $deleteUrl;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlBuilder $actionUrlBuilder,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        $editUrl = self::POST_URL_PATH_EDIT,
        $deleteUrl = self::POST_URL_PATH_DELETE
    ) {
        $this->urlBuilder       = $urlBuilder;
        $this->actionUrlBuilder = $actionUrlBuilder;
        $this->editUrl          = $editUrl;
        $this->deleteUrl          = $deleteUrl;

        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $name = $this->getData('name');
                if (isset($item['brand_id'])) {
                    $item[$name]['edit'] = [
                        'href'  => $this->urlBuilder->getUrl($this->editUrl, [
                            'brand_id' => $item['brand_id'],
                        ]),
                        'label' => __('Edit'),
                    ];
                    $item[$name]['delete'] = [
                        'href'  => $this->urlBuilder->getUrl($this->deleteUrl, [
                            'brand_id' => $item['brand_id'],
                        ]),
                        'label' => __('Delete'),
                    ];
                }
            }
        }

        return $dataSource;
    }
}
