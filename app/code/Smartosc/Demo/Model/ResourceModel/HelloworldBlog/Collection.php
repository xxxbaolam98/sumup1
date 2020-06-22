<?php
namespace Smartosc\Demo\Model\ResourceModel\HelloworldBlog;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'blog_id';
//    protected $_eventPrefix = 'smartosc_demo_helloworldblog_collection';
//    protected $_eventObject = 'helloworldblog_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Smartosc\Demo\Model\HelloworldBlog', 'Smartosc\Demo\Model\ResourceModel\HelloworldBlog');
    }

}
