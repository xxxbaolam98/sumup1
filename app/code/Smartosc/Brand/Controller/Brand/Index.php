<?php
namespace Smartosc\Brand\Controller\Brand;

use Magento\Framework\App\Action\Context;

class Index extends \Magento\Framework\App\Action\Action
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

    protected $resultPageFactory;

    /**
     * @param Context                                             $context
     * @param \Magento\Store\Model\StoreManager                   $storeManager
     * @param \Magento\Framework\View\Result\PageFactory          $resultPageFactory
     * @param \Smartosc\Brand\Helper\Data                              $brandHelper
     * @param \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory
     */
    public function __construct(
        Context $context,
        \Magento\Store\Model\StoreManager $storeManager,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Smartosc\Brand\Helper\Data $brandHelper,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_brandHelper = $brandHelper;
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }

    /**
     * Default customer account page
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $page = $this->resultPageFactory->create();
        return $page;
    }
}
