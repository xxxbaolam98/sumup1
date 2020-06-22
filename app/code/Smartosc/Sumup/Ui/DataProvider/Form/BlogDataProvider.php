<?php
namespace Smartosc\Sumup\Ui\DataProvider\Form;

use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Smartosc\Sumup\Model\Blog\FileInfo;
use Smartosc\Sumup\Model\Config;

class BlogDataProvider extends \Magento\Ui\DataProvider\ModifierPoolDataProvider
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
        \Smartosc\Sumup\Model\ResourceModel\Sumup\Grid\CollectionFactory $collectionFactory,
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
        foreach ($items as $blog) {
            $blog_arr = $blog->getData();
            $this->convertValues($blog);
            $this->_loadedData[$blog_arr['blog_id']] = $blog->getData();
            $this->_loadedData[$blog_arr['blog_id']]['links']['products'] = [];
            foreach ($blog['products'] as $product_id) {
                $product = $this->product->create();
                $productBlog = $product->load($product_id);
                $this->_loadedData[$blog_arr['blog_id']]['links']['products'][] = [
                    'id'        => $product->getId(),
                    'product_name'      => $product->getName(),
                    'status'    => $this->status->getOptionText($productBlog->getStatus()),
                    'thumbnail' => $this->imageHelper->init($productBlog, 'product_listing_thumbnail')->getUrl(),
                    'price'      => "$" . $product->getFinalPrice()
                ];
            }
            if (!empty($blog_arr['thumbnail_path'])) {
                $this->_loadedData[$blog_arr['blog_id']]['thumbnail_path'] = [
                    [
                        'name' => $blog_arr['thumbnail_path'],
                        'url'  =>  $this->config->getMediaUrl($blog_arr['thumbnail_path']),
                        'size' => filesize($this->config->getMediaPath($blog_arr['thumbnail_path'])),
                        'type' => 'image',
                    ],
                ];
            }
        }
        $data = $this->dataPersistor->get('blog');
        if (!empty($data)) {
            $blog = $this->collection->getNewEmptyItem();
            $blog->setData($data);
            $this->_loadedData[$blog->getId()] = $blog->getData();
            $this->dataPersistor->clear('blog');
        }
        return $this->_loadedData;
    }

    private function convertValues($blog)
    {
        $fileName = $blog->getThumbnail();
        $image = [];
        if ($this->getFileInfo()->isExist($fileName)) {
            $stat = $this->getFileInfo()->getStat($fileName);
            $mime = $this->getFileInfo()->getMimeType($fileName);
            $image[0]['name'] = $fileName;
            $image[0]['url'] = $blog->getImageUrl();
            $image[0]['size'] = isset($stat) ? $stat['size'] : 0;
            $image[0]['type'] = $mime;
        }
        $blog->setThumbnail($image);

        return $blog;
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
