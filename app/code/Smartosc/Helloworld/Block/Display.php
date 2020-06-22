<?php
namespace Smartosc\Helloworld\Block;
use Magento\Store\Model\StoreManagerInterface;

class Display extends \Magento\Framework\View\Element\Template
{
    /**
     * @var QuoteEmail
     */
    private $storeManager;

    public function __construct(StoreManagerInterface $storeManager,\Magento\Framework\View\Element\Template\Context $context,  array $data = []
    )
    {
        $this->storeManager = $storeManager;
        parent::__construct($context,$data);
    }

    public function sayHello()
    {
        $username = $this->getData("username");
        return __('Hello World ' . $username);
    }

}
