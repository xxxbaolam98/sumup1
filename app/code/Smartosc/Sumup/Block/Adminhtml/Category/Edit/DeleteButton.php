<?php
namespace Smartosc\Sumup\Block\Adminhtml\Category\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    public function getButtonData()
    {
        $data = [];
        if ($this->getCategoryId()) {
            $data = [
                'label' => __('Delete'),
                'class' => 'delete primary',
                'on_click' => 'deleteConfirm(\'' . __(
                        'Are you sure you want to do this?'
                    ) . '\', \'' . $this->getDeleteUrl() . '\')',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    public function getDeleteUrl() {
        return $this->getUrl('sumup/category/delete', ['category_id' => $this->getCategoryId()]);
    }
}
