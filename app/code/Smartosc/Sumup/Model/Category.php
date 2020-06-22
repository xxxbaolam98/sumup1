<?php
namespace Smartosc\Sumup\Model;
use Smartosc\Sumup\Api\Data\CategoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\LocalizedException;
class Category extends \Magento\Framework\Model\AbstractModel implements CategoryInterface
{

    protected function _construct()
    {
        $this->_init('Smartosc\Sumup\Model\ResourceModel\Category');
    }


    public function getId()
    {
        return parent::getData(self::CATEGORY_ID);
    }

    public function getName()
    {
        return $this->getData(self::CATEGORY_NAME);
    }

    public function getParentId()
    {
        return $this->getData(self::PARENT_ID);
    }

    public function setId($id)
    {
        return $this->setData(self::CATEGORY_ID, $id);
    }

    public function setName($title)
    {
        return $this->setData(self::CATEGORY_NAME, $title);
    }

    public function setParentId($parentId)
    {
        return $this->setData(self::PARENT_ID, $parentId);
    }


}

