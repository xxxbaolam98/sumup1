<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Smartosc\Demo\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Upgrade Data script
 */

class UpgradeData implements UpgradeDataInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if ($context->getVersion()
            && version_compare($context->getVersion(), '0.0.3') < 0
        ) {
            $table = $setup->getTable('helloworld_blog');
            $setup->getConnection()
                ->insertForce($table, ['title' => 'Lesson 2', 'content' => 'blablo 2', 'author' => 'Long']);

            $setup->getConnection()
                ->update($table, ['author' => 'Vu'], 'blog_id = 1');

            $tablecomment = $setup->getTable('helloworld_blog_comment');

            $setup->getConnection()
                ->insertForce($tablecomment, ['blog_id' => 1, 'content' => 'blablo comment 2', 'author' => 'Long']);
        }
        $setup->endSetup();
    }
}
