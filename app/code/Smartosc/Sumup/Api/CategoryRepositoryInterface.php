<?php
namespace Smartosc\Sumup\Api;

interface CategoryRepositoryInterface
{
    public function save(\Smartosc\Sumup\Api\Data\CategoryInterface $category);

    public function getById($categoryId);

    public function delete(\Smartosc\Sumup\Api\Data\CategoryInterface $category);

    public function deleteById($categoryId);
}
?>
