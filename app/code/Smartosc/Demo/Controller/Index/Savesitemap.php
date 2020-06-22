<?php
namespace Smartosc\Demo\Controller\Index;
use \Magento\Framework\Controller\ResultFactory;


class Savesitemap extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    private $_testsitemapFactory;
    protected $_messageManager;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Smartosc\Demo\Model\TestSiteMapFactory $testsitemapFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager
    )
    {
        $this->_pageFactory = $pageFactory;
        $this->_testsitemapFactory = $testsitemapFactory;
        $this->_messageManager =$messageManager;

        return parent::__construct($context);
    }

    public function execute()
    {
        $sitemap = $this->getRequest()->getPostValue();

        if (empty($sitemap["sitemap_type"]) || empty($sitemap["store_id"]) || empty($sitemap["sitemap_filename"])
            || empty($sitemap["sitemap_path"]) || empty($sitemap["sitemap_time"]) || is_int($sitemap["store_id"])) {
            $this->_messageManager->addErrorMessage('Your Error Message');
        } else {
            $this->_messageManager->addSuccessMessage('Your Success Message');

            $testsitemap = $this->_testsitemapFactory->create();
//        $testsitemap->setData("sitemap_type", $sitemap["sitemap_type"]);
//        $testsitemap->setData("sitemap_filename", $sitemap["sitemap_filename"]);
//        $testsitemap->setData("sitemap_path", $sitemap["sitemap_path"]);
//        $testsitemap->setData("sitemap_time", $sitemap["sitemap_time"]);
//        $testsitemap->setData("store_id", $sitemap["store_id"]);
//        $testsitemap->save();
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }


}

