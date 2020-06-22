<?php

namespace Smartosc\Sumup\Block\Adminhtml\Blog\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        return [
            'label'          => __('Save'),
            'class'          => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save',
                    'target' => '#blog_form',
                    'eventData' => ['action' => 'sumup/blog/save']]
                ],
                'form-role' => 'save',
            ],
            'sort_order'     => 90,
        ];
    }
}
