<?php

namespace Smartosc\Brand\Block\Adminhtml\Brand\Edit;

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
                    'target' => '#brand_form',
                    'eventData' => ['action' => 'brand/brand/save']]
                ],
                'form-role' => 'save',
            ],
            'sort_order'     => 90,
        ];
    }
}
