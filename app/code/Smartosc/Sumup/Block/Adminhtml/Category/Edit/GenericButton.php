<?php
namespace Smartosc\Sumup\Block\Adminhtml\Category\Edit;
use Magento\Backend\Block\Widget\Context;
use Smartosc\Sumup\Api\CategoryRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;


class GenericButton
{
    /**
     * @var Context
     */
    private $context;

    protected $categoryRepository;

    public function __construct(
        Context $context,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->context = $context;
        $this->categoryRepository = $categoryRepository;
    }

    public function getCategoryId()
    {
        try {
            return $this->categoryRepository->getById(
                $this->context->getRequest()->getParam('category_id')
            )->getId();
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
