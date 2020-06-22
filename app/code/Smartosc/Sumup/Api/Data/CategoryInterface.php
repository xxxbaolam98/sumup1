<?php
namespace Smartosc\Sumup\Api\Data;

interface CategoryInterface
{
    const CATEGORY_ID = 'category_id';
    const CATEGORY_NAME  = 'category_name';
    const PARENT_ID = 'parent_id';

    public function getId();

    public function getName();

    public function getParentId();

    public function setId($id);

    public function setName($name);

    public function setParentId($parentId);
}
?>
