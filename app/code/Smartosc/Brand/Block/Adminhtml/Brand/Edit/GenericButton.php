<?php
namespace Smartosc\Brand\Block\Adminhtml\Brand\Edit;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Exception\NoSuchEntityException;


class GenericButton
{
    /**
     * @var Context
     */
    private $context;


    public function __construct(
        Context $context
    ) {
        $this->context = $context;
    }

    public function getBrandId()
    {
        try {
            return
                $this->context->getRequest()->getParam('brand_id');
        } catch (NoSuchEntityException $e) {
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}

