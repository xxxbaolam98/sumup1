<?php
namespace Smartosc\Brand\Ui\DataProvider\Form;

use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Smartosc\Brand\Model\Brand\FileInfo;
use Smartosc\Brand\Model\Config;

class BrandDataProvider extends \Magento\Ui\DataProvider\ModifierPoolDataProvider
{
    protected $_loadedData;

    protected $dataPersistor;

    protected $collection;

    private $fileInfo;

    private $product;

    private $status;

    private $imageHelper;

    private $config;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Smartosc\Brand\Model\ResourceModel\Brand\Form\CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        PoolInterface $pool = null,
        array $meta = [],
        array $data = [],
        ProductFactory $product,
        Status $status,
        ImageHelper $imageHelper,
        Config $config
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->product = $product;
        $this->status         = $status;
        $this->imageHelper    = $imageHelper;
        $this->config         = $config;
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
        foreach ($items as $brand) {
            $brand_arr = $brand->getData();
            $this->convertValues($brand);
            $this->_loadedData[$brand_arr['brand_id']] = $brand->getData();
            $this->_loadedData[$brand_arr['brand_id']]['links']['products'] = [];
            foreach ($brand['products'] as $product_id) {
                $product = $this->product->create();
                $productBlog = $product->load($product_id);
                $this->_loadedData[$brand_arr['brand_id']]['links']['products'][] = [
                    'id'        => $product->getId(),
                    'product_name'      => $product->getName(),
                    'status'    => $this->status->getOptionText($productBlog->getStatus()),
                    'thumbnail' => $this->imageHelper->init($productBlog, 'product_listing_thumbnail')->getUrl(),
                    'price'      => "$" . $product->getFinalPrice()
                ];
            }
            if (!empty($brand_arr['thumbnail'])) {
                $this->_loadedData[$brand_arr['brand_id']]['thumbnail'] = [
                    [
                        'name' => $brand_arr['thumbnail'],
                        'url'  =>  $this->config->getMediaUrl($brand_arr['thumbnail']),
                        'size' => filesize($this->config->getMediaPath($brand_arr['thumbnail'])),
                        'type' => 'image',
                    ],
                ];
            }

            if (!empty($brand_arr['image'])) {
                $this->_loadedData[$brand_arr['brand_id']]['image'] = [
                    [
                        'name' => $brand_arr['image'],
                        'url'  =>  $this->config->getMediaUrl($brand_arr['image']),
                        'size' => filesize($this->config->getMediaPath($brand_arr['image'])),
                        'type' => 'image',
                    ],
                ];
            }
        }
        $data = $this->dataPersistor->get('brand');
        if (!empty($data)) {
            $blog = $this->collection->getNewEmptyItem();
            $blog->setData($data);
            $this->_loadedData[$blog->getId()] = $blog->getData();
            $this->dataPersistor->clear('brand');
        }

        return $this->_loadedData;
    }

    private function convertValues($brand)
    {
        $fileName = $brand->getThumbnail();
        $image = [];
        if ($this->getFileInfo()->isExist($fileName)) {
            $stat = $this->getFileInfo()->getStat($fileName);
            $mime = $this->getFileInfo()->getMimeType($fileName);
            $image[0]['name'] = $fileName;
            $image[0]['url'] = $brand->getThumbnailUrl();
            $image[0]['size'] = isset($stat) ? $stat['size'] : 0;
            $image[0]['type'] = $mime;
        }
        $brand->setThumbnail($fileName);

        return $brand;
    }

    /**
     * Get FileInfo instance
     *
     * @return FileInfo
     *
     * @deprecated 101.1.0
     */
    private function getFileInfo()
    {
        if ($this->fileInfo === null) {
            $this->fileInfo = ObjectManager::getInstance()->get(FileInfo::class);
        }
        return $this->fileInfo;
    }
}
