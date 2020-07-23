<?php
namespace Smartosc\Brand\Block\Brand;
use Magento\Backend\Block\Template;

class Search extends Template
{

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Smartosc\Brand\Helper\Data
     */
    protected $_brandHelper;

    /**
     * @var \Smartosc\Brand\Model\BrandFactory
     */
    protected $_brandFactory;
    protected $_request;
    private $_collection;

    /**
     * @param Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Smartosc\Brand\Helper\Data $brandHelper
     * @param \Smartosc\Brand\Model\BrandFactory $brandFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Smartosc\Brand\Helper\Data $brandHelper,
        \Smartosc\Brand\Model\BrandFactory $brandFactory,
        array $data = []
    ) {
        $this->_brandFactory = $brandFactory;
        $this->_coreRegistry = $registry;
        $this->_brandHelper = $brandHelper;
        $this->_request = $context->getRequest();
        parent::__construct($context, $data);
    }

    public function _construct()
    {
        parent::_construct();

        $template = 'group/brandlist.phtml';

        if (!$this->hasData('template')) {
            $this->setTemplate($template);
        }
    }

    /**
     * Set brand collection
     * @param \Smartosc\Brand\Model\Brand
     */
    public function setCollection($collection)
    {
        $this->_collection = $collection;
        return $this->_collection;
    }


    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->pageConfig->getTitle()->set(__('Brand Search Result'));
        $pager = $this->getLayout()->createBlock(
            'Magento\Theme\Block\Html\Pager',
            'brand.history.pager'
        )->setAvailableLimit([5 => 5, 10 => 10, 15 => 15, 20 => 20])
            ->setShowPerPage(false)->setCollection(
                $this->getBrandCollection()
            );
        $this->setChild('pager', $pager);
        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }


    public function getBrandCollection()
    {
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = 5;
        $searchKey = ($this->getRequest()->getParam('s')) ? $this->getRequest()->getParam('s') : "";

        $brand = $this->_brandFactory->create();
        $brandCollection = $brand->getCollection();
        $brandCollection->addFieldToFilter('status', 1);
        $brandCollection->addFieldToFilter(['name'], [
            ['like' => '%' . $searchKey . '%']
        ]);
        $brandCollection->setPageSize($pageSize);
        $brandCollection->setCurPage($page);

        $this->setCollection($brandCollection);

        return $this->_collection;
    }

    public function getConfig($key, $default = '')
    {
        $result = $this->_brandHelper->getConfig($key);
        if (!$result) {
            return $default;
        }
        return $result;
    }


}
