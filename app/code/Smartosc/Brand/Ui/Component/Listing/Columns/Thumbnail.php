<?php
namespace Smartosc\Brand\Ui\Component\Listing\Columns;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Smartosc\Brand\Model\BrandFactory as BrandModel;

class Thumbnail extends \Magento\Ui\Component\Listing\Columns\Column
{
    const NAME = 'thumbnail';

    const ALT_FIELD = 'name';

    /**
     * @param \Magento\Framework\Filesystem $filesystem
     */
    protected $filesystem;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /** @var brandModel */
    protected $brandModel;

    protected $urlBuilder;

    protected $imageHelper;

    /**
     * @param ContextInterface                           $context
     * @param UiComponentFactory                         $uiComponentFactory
     * @param \Magento\Catalog\Helper\Image              $imageHelper
     * @param \Magento\Framework\UrlInterface            $urlBuilder
     * @param \Magento\Framework\Filesystem              $filesystem
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param BrandModel                                 $brandModel
     * @param array                                      $components
     * @param array                                      $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        BrandModel $brandModel,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->imageHelper = $imageHelper;
        $this->urlBuilder = $urlBuilder;
        $this->filesystem = $filesystem;
        $this->_storeManager = $storeManager;
        $this->brandModel = $brandModel;
    }

    public function prepareDataSource(array $dataSource)
    {

        /** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */
        $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $mediaFolder = 'smartosc/brand/';

        $path = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as &$item) {
                if (!isset($item['thumbnail'])) {
                    continue;
                }
                $brand = $this->brandModel->create();
                $thumbrand = $brand->load($item['brand_id']);
                $thumbnailUrl = $thumbrand->getThumbnailUrl();

                $item[$fieldName . '_src'] = $thumbnailUrl;
                $item[$fieldName . '_alt'] = $item['name'];
                $item[$fieldName . '_link'] = $this->urlBuilder->getUrl(
                    'brand/brand/edit',
                    ['brand_id' => $item['brand_id'], 'store' => $this->context->getRequestParam('store')]
                );
                $item[$fieldName . '_orig_src'] = $thumbnailUrl;
            }
        }
        return $dataSource;
    }

    /**
     * @param array $row
     *
     * @return null|string
     */
    protected function getAlt($row)
    {
        $altField = $this->getData('config/altField') ?: self::ALT_FIELD;
        return isset($row[$altField]) ? $row[$altField] : null;
    }
}
