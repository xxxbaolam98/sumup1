<?php
namespace Smartosc\Demo\Model\ResourceModel\TestSiteMap;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'sitemap_id';
//    protected $_eventPrefix = 'smartosc_demo_helloworldblog_SiteMapCollection';
//    protected $_eventObject = 'helloworldblog_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Smartosc\Demo\Model\TestSiteMap', 'Smartosc\Demo\Model\ResourceModel\TestSiteMap');
    }

}
