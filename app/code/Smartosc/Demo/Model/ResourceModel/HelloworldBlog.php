<?php
namespace Smartosc\Demo\Model\ResourceModel;


class HelloworldBlog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('helloworld_blog', 'blog_id');
    }

}
