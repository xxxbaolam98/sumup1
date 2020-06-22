<?php
namespace Smartosc\Sumup\Plugin;

class Topmenu
{
    public function __construct(
        \Magento\Customer\Model\Session $session
    ) {
        $this->Session = $session;
    }


    public function afterGetHtml(\Magento\Theme\Block\Html\Topmenu $topmenu, $html)
    {
        $swappartyUrl = $topmenu->getUrl('sumup/index/bloglist');//here you can set link
        $magentoCurrentUrl = $topmenu->getUrl('sumup/index/bloglist', ['_current' => true, '_use_rewrite' => true]);
        if (strpos($magentoCurrentUrl,'sumup/custommenu') !== false) {
            $html .= "<li class=\"level0 nav-5 active level-top parent ui-menu-item\">";
        } else {
            $html .= "<li class=\"level0 nav-4 level-top parent ui-menu-item\">";
        }
        $html .= "<a href=\"" . $swappartyUrl . "\" class=\"level-top ui-corner-all\"><span>" . __("Blogs") . "</span></a>";
        $html .= "</li>";
        return $html;
    }
}
