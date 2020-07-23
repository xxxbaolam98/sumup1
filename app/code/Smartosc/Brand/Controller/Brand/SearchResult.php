<?php
namespace Smartosc\Brand\Controller\Brand;

use Magento\Framework\App\Action\Context;

/**
 * Display Hello on screen
 */
class SearchResult extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;

    /**
     * @var \Magento\Framework\App\ResponseInterface
     */
    protected $_response;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @var \Magento\Framework\Controller\ResultFactory
     */
    protected $resultFactory;

    /**
     * @var \Smartosc\Brand\Helper\Data
     */
    protected $_brandHelper;

    /**
     * @var \Magento\Framework\Controller\Result\ForwardFactory
     */
    protected $resultForwardFactory;
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;
    /**
     * @var \Magento\Framework\Registry
     */
    private $_coreRegistry;

    /**
     * @param Context
     * @param \Magento\Store\Model\StoreManager
     * @param \Magento\Framework\View\Result\PageFactory
     * @param \Smartosc\Brand\Helper\Data
     * @param \Magento\Framework\Controller\Result\ForwardFactory
     * @param \Magento\Framework\Registry
     */
    public function __construct(
        Context $context,
        \Magento\Store\Model\StoreManager $storeManager,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Smartosc\Brand\Helper\Data $brandHelper,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\Registry $registry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_brandHelper = $brandHelper;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
        $this->_request = $context->getRequest();
    }

    /**
     * Default customer account page
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $page = $this->resultPageFactory->create();
        $brandHelper = $this->_brandHelper;
        $request = $this->getRequest();
//        if (!$brandHelper->getConfig('general_settings/enable') || !trim($this->_request->getParam('s'))) {
//            $resultForward = $this->resultForwardFactory->create();
//            return $resultForward->forward('defaultnoroute');
//        }
        return $page;
    }
}
