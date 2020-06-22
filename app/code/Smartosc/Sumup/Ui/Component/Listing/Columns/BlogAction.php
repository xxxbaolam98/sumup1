<?php

namespace Smartosc\Sumup\Ui\Component\Listing\Columns;

use Magento\Cms\Block\Adminhtml\Page\Grid\Renderer\Action\UrlBuilder;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

class BlogAction extends \Magento\Ui\Component\Listing\Columns\Column
{
    /** Url path */
    const POST_URL_PATH_EDIT = 'sumup/blog/edit';
    const POST_URL_PATH_DELETE = 'sumup/blog/delete';

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
                if (isset($item['blog_id'])) {
                    $item[$name]['edit'] = [
                        'href'  => $this->urlBuilder->getUrl($this->editUrl, [
                            'blog_id' => $item['blog_id'],
                        ]),
                        'label' => __('Edit'),
                    ];
                    $item[$name]['delete'] = [
                        'href'  => $this->urlBuilder->getUrl($this->deleteUrl, [
                            'blog_id' => $item['blog_id'],
                        ]),
                        'label' => __('Delete'),
                    ];
                }
            }
        }

        return $dataSource;
    }
}
