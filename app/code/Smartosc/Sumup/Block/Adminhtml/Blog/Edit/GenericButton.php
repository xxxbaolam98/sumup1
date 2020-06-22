<?php
namespace Smartosc\Sumup\Block\Adminhtml\Blog\Edit;
use Magento\Backend\Block\Widget\Context;
//use Smartosc\Sumup\Api\BlogRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;


class GenericButton
{
    /**
     * @var Context
     */
    private $context;

//    protected $blogRepository;

    public function __construct(
        Context $context
//        BlogRepositoryInterface $blogRepository
    ) {
        $this->context = $context;
//        $this->blogRepository = $categoryRepository;
    }

    public function getBlogId()
    {
        try {
            return
                $this->context->getRequest()->getParam('blog_id');
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

