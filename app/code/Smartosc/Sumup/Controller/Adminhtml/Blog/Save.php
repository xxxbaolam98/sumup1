<?php

namespace Smartosc\Sumup\Controller\Adminhtml\Blog;

class Save extends \Magento\Backend\App\Action
{
    protected $_pageFactory;
    protected $_blogFactory;
    protected $_messageManager;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Smartosc\Sumup\Model\BlogFactory $blogFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        parent::__construct($context);
        $this->_pageFactory = $pageFactory;
        $this->_blogFactory = $blogFactory;
        $this->_messageManager =$messageManager;
    }

    public function execute()
    {
        $arr = $this->getRequest()->getParams();
        $resultRedirect = $this->resultRedirectFactory->create();
        $blog_id = 0;
        if (array_key_exists('blog_id', $arr) && !empty($arr['blog_id'])) {
            $blog_id = $arr['blog_id'];
        }

        if ($this->validation($arr) == true && $this->uniqueName($arr['name'], $blog_id) == false) {
            $name = $arr['name'];
            $short_des = $arr['short_description'];
            $des = $arr['description'];
            $status_id = $arr['status_id'];
            $categories = $arr['category_id'];
            $content = "";
            $publish_date_from = "";
            $publish_date_to = "";
            $thumbnail_path = "";
            $url_key = "";

            if (array_key_exists('publish_date_from', $arr)) {
                $publish_date_from = $arr['publish_date_from'];
            }
            if (array_key_exists('publish_date_to', $arr)) {
                $publish_date_to = $arr['publish_date_to'];
            }
            if (array_key_exists('url_key', $arr)) {
                $url_key = $arr['url_key'];
            }
            if (array_key_exists('content', $arr)) {
                $content = $arr['content'];
            }
            if (array_key_exists('thumbnail_path', $arr) && is_array($arr['thumbnail_path'])) {
                $thumbnail_path = $arr['thumbnail_path'][0]['name'];
            }
            $blog =  $this->_blogFactory->create();
            $collection = $blog->getCollection();
            if (array_key_exists('blog_id', $arr) && !empty($arr['blog_id'])) {
                $blog->load($arr['blog_id']);
                $collection->deleteBlogCategory($arr['blog_id']);
                $collection->deleteBlogTag($arr['blog_id']);
                $collection->deleteBlogProducts($arr['blog_id']);
            }
            $blog->setData("name", $name);
            $blog->setData("short_description", $short_des);
            $blog->setData("description", $des);
            $blog->setData("content", $content);
            $blog->setData("publish_date_from", $publish_date_from);
            $blog->setData("publish_date_to", $publish_date_to);
            $blog->setData("url_key", $url_key);
            $blog->setData("status_id", $status_id);
            $blog->setData("thumbnail_path", $thumbnail_path);
            $blog->save();
            $blog_id = $blog->getId();

            if (!empty($categories) && $categories != null && is_array($categories)) {
                $collection->insertBlogCategory($blog_id, $categories);
            }
            if (array_key_exists('tag_id', $arr) && $arr['tag_id'] != null && is_array($arr['tag_id'])) {
                $collection->insertBlogTag($blog_id, $arr['tag_id']);
            }
            if (array_key_exists('links', $arr) && $arr['links']['products'] != null && is_array($arr['links']['products'])) {
                $collection->insertBlogProducts($blog_id, $arr['links']['products']);
            }
            $this->_messageManager->addSuccessMessage('Save Successfully');
            $resultRedirect->setPath('sumup/blog/edit', ["blog_id"=> $blog_id]);
        } else {
            $this->_messageManager->addErrorMessage('Invalid Input');
            if (array_key_exists('blog_id', $arr)) {
                $resultRedirect->setPath('sumup/blog/edit', ["blog_id"=> $blog_id]);
            } else {
                $resultRedirect->setPath('sumup/blog/edit');
            }
        }
        return $resultRedirect;
    }

    public function validation($arr)
    {
        if (!array_key_exists('name', $arr) || empty($arr['name']) || !array_key_exists('status_id', $arr) || empty($arr['status_id']) || !array_key_exists('category_id', $arr) || empty($arr['category_id'])
            || !array_key_exists('short_description', $arr) || empty($arr['short_description']) || !array_key_exists('description', $arr) || empty($arr['description'])) {
            return false;
        }
        if (array_key_exists('publish_date_from', $arr) && array_key_exists('publish_date_to', $arr) && strtotime($arr['publish_date_from']) > strtotime($arr['publish_date_to'])) {
            return false;
        }
        return true;
    }

    public function uniqueName($blogName, $blog_id)
    {
        $blog =  $this->_blogFactory->create();
        $collection = $blog->getCollection();
        foreach ($collection as $item) {
            if ($blogName == $item->getData('name') && $blog_id != $item['blog_id']) {
                return true;
            }
        }
        return false;
    }
}
