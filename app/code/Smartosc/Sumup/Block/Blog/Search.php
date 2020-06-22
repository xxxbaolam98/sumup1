<?php

namespace Smartosc\Sumup\Block\BLog;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Smartosc\Sumup\Model\Url;

class Search extends Template
{
    /**
     * @var Url
     */
    protected $url;

    /**
     * @param Url     $url
     * @param Context $context
     * @param array   $data
     */
    public function __construct(
        Url $url,
        Context $context,
        array $data = []
    ) {
        $this->url = $url;

        parent::__construct($context, $data);
    }
}
