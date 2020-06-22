<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Smartosc\Demo\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /**
         * Install messages
         */
        $data = [
            ['title' => 'Lesson 1',
                'content' => "blablbo "]
        ];
        foreach ($data as $bind) {
            $setup->getConnection()
                ->insertForce($setup->getTable('helloworld_blog'), $bind);
        }
    }
}
