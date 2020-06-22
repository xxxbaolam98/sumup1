<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Smartosc\Demo\Setup;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        /**
         * Create table 'helloworld_blog'
         */
        $table = $setup->getConnection()
            ->newTable($setup->getTable('helloworld_blog'))
            ->addColumn(
                'blog_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Blog ID'
            )
            ->addColumn(
                'title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false, 'default' => ''],
                'title'
            )
            ->addColumn(
                'content',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false, 'default' => ''],
                'content'
            )
            ->setComment("Helloworld Blog table");
        $setup->getConnection()->createTable($table);

        /**
         * Create table 'helloworld_blog_comment'
         */
        $tablecomment = $setup->getConnection()
            ->newTable($setup->getTable('helloworld_blog_comment'))
            ->addColumn(
                'comment_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Comment ID'
            )
            ->addColumn(
                'blog_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' =>true],
                'Blog ID'
            )
            ->addColumn(
                'content',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false, 'default' => ''],
                'content'
            )
            ->setComment("Helloworld Blog table");
        $setup->getConnection()->createTable($tablecomment);
    }
}
