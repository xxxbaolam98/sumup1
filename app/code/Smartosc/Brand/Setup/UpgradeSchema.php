<?php
namespace Smartosc\Brand\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;

        $installer->startSetup();

        if (version_compare($context->getVersion(), '0.0.2', '<')) {
            $installer->getConnection()->addColumn(
                $installer->getTable('smartosc_brand'),
                'is_feature',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'size' => null,
                    'nullable' => true,
                    'comment' => 'Is Feature Brand'
                ]
            );
        }
        $installer->endSetup();
    }
}
