<?php
namespace Smartosc\Sumup\Model\ResourceModel;


class Status extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('sumup_blog_status', 'status_id');
    }

}
