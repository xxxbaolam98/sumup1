<?php
namespace Smartosc\Demo\Model;
class HelloworldBlog extends \Magento\Framework\Model\AbstractModel
{
//    const CACHE_TAG = 'smartosc_demo_helloworldblog';
//
//    protected $_cacheTag = 'smartosc_demo_helloworldblog';
//
//    protected $_eventPrefix = 'smartosc_demo_helloworldblog';

    protected function _construct()
    {
        $this->_init('Smartosc\Demo\Model\ResourceModel\HelloworldBlog');
    }

//    public function getIdentities()
//    {
//        return [self::CACHE_TAG . '_' . $this->getId()];
//    }
//
//    public function getDefaultValues()
//    {
//        $values = [];
//
//        return $values;
//    }
}
