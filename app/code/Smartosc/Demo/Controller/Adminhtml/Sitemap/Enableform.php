<?php
namespace Smartosc\Demo\Controller\Adminhtml\Sitemap;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 */
class Enableform extends Action implements HttpGetActionInterface
{

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);

        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Load the page defined in view/adminhtml/layout/helloworld_index_index.xml
     *
     * @return Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();


        return $resultPage;
    }
}

