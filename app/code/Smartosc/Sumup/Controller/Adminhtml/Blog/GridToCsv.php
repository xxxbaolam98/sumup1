<?php
namespace Smartosc\Sumup\Controller\Adminhtml\Blog;

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Smartosc\Sumup\Model\BlogFactory;

/**
 * Class GridToCsv
 * @package NgocThanh\Blog\Controller\Adminhtml\Export
 */
class GridToCsv extends Action
{
    protected $_colectionFactory;

    protected $_fileFactory;

    protected $directory;

    protected $_blogFactory;

    /**
     * GridToCsv constructor.
     * @param Action\Context $context
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Smartosc\Sumup\Model\ResourceModel\Blog\CollectionFactory $collectionFactory
     * @param BlogFactory $blogFactory
     * @throws FileSystemException
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Smartosc\Sumup\Model\ResourceModel\Blog\CollectionFactory $collectionFactory,
        BlogFactory $blogFactory
    ) {
        $this->_blogFactory = $blogFactory;
        $this->_colectionFactory = $collectionFactory;
        $this->_fileFactory = $fileFactory;
        $this->directory = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        parent::__construct($context);
    }

    public function execute()
    {
        $listBlog = $this->_colectionFactory->create()->getData();
        $date = date('m_d_Y_H_i_s');
        $filepath = 'export/custom' . $date . '.csv';
        try {
            $this->directory->create('export');
        } catch (FileSystemException $e) {
        }
        $stream = $this->directory->openFile($filepath, 'w+');
        $stream->lock();
        $columns = $this->getColumnHeader();
        foreach ($columns as $column) {
            $header[] = $column;
        }
        $stream->writeCsv($header);

        foreach ($listBlog as $item) {
            $stream->writeCsv($item);
        }

        $content = [];
        $content['type'] = 'filename'; // must keep filename
        $content['value'] = $filepath;
        $content['rm'] = '1'; //remove csv from var folder
        $csvfilename = 'blog_' . $date . '.csv';
        return $this->_fileFactory->create($csvfilename, $content, DirectoryList::VAR_DIR);
    }

    /**
     * @return string[]
     */
    public function getColumnHeader()
    {
        $listBlog = $this->_colectionFactory->create();
        $keys = [];
        foreach ($listBlog as $blog) {
            $keys = array_keys($blog->toArray());
            break;
        }
        return $keys;
    }
}
