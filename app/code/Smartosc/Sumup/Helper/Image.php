<?php
namespace Smartosc\Sumup\Helper;
use Magento\Framework\UrlInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Filesystem\DirectoryList;
class Image extends AbstractHelper
{
    /**
     * media sub folder
     * @var string
     */
    protected $subDir = 'smartosc/tmp'; //actual path is pub/media/smartosc/

    /**
     * url builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;
    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $fileSystem;

    protected $scopeConfig;
    protected $storeManager;
    protected $messageManager;
    protected $_response;
    protected $_resourceConfig;
    protected $_responseFactory;
    protected $_directory;
    protected $_imageFactory;

    /**
     * @param UrlInterface $urlBuilder
     * @param Filesystem $fileSystem
     */
    public function __construct(
        UrlInterface $urlBuilder,
        Filesystem $fileSystem,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\ResponseInterface $response,
        \Magento\Framework\App\Config\Storage\WriterInterface $resourceConfig,
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\Image\AdapterFactory $imageFactory

    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->_storeManager=$storeManager;
        $this->messageManager=$messageManager;
        $this->_response=$response;
        $this->_resourceConfig=$resourceConfig;
        $this->_responseFactory = $responseFactory;
        $this->urlBuilder = $urlBuilder;
        $this->fileSystem = $fileSystem;
        $this->_directory = $fileSystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->_imageFactory = $imageFactory;
    }
    /**
     * get images base url
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]).$this->subDir.'/sumup/';
    }


}
