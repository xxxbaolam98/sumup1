<?php

namespace Smartosc\Helloworld\Block\Adminhtml;

class Adminblock extends \Magento\Backend\Block\Widget\Container
{
    protected $authSession;


    public function __construct(\Magento\Backend\Block\Widget\Context $context,array $data = [],\Magento\Backend\Model\Auth\Session $authSession)
    {
        $this->authSession = $authSession;
        parent::__construct($context, $data);

    }

    public function greet()
    {
        $user = $this->authSession->getUser();
        $username = $user->getUsername();
        return 'Hello world, ' . $username;
    }

}
