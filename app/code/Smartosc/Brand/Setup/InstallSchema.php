<?php

namespace Smartosc\Brand\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * @var \Magento\Eav\Model\Entity\Type
     */
    protected $_entityTypeModel;

    /**
     * @var \Magento\Eav\Model\Entity\Attribute
     */
    protected $_catalogAttribute;

    /**
     * @var \Magento\Eav\Setup\EavSetup
     */
    protected $_eavSetup;

    /**
     * @param \Magento\Eav\Setup\EavSetup         $eavSetup
     * @param \Magento\Eav\Model\Entity\Type      $entityType
     * @param \Magento\Eav\Model\Entity\Attribute $catalogAttribute
     */
    public function __construct(
        \Magento\Eav\Setup\EavSetup $eavSetup,
        \Magento\Eav\Model\Entity\Type $entityType,
        \Magento\Eav\Model\Entity\Attribute $catalogAttribute
    ) {
        $this->_eavSetup = $eavSetup;
        $this->_entityTypeModel = $entityType;
        $this->_catalogAttribute = $catalogAttribute;
    }

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $entityTypeModel = $this->_entityTypeModel;
        $catalogAttributeModel = $this->_catalogAttribute;
        $installer =  $this->_eavSetup;

        $setup->startSetup();

        $setup->getConnection()->dropTable($setup->getTable('smartosc_brand'));

        $table = $setup->getConnection()
            ->newTable($setup->getTable('smartosc_brand'))
            ->addColumn(
                'brand_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Brand ID'
            )
            ->addColumn(
                'name',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Brand Name'
            )
            ->addColumn(
                'url_key',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Brand Url Key'
            )
            ->addColumn(
                'description',
                Table::TYPE_TEXT,
                '64k',
                ['nullable' => false],
                'Brand Description'
            )
            ->addColumn(
                'image',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Brand Image'
            )
            ->addColumn(
                'thumbnail',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Brand Thumbnail'
            )
            ->addColumn(
                'page_layout',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Page Layout'
            )->addColumn(
                'layout_update_xml',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '64k',
                ['nullable' => true],
                'Page Layout Update Content'
            )->addColumn(
                'status',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '1'],
                'Status'
            )
            ->addColumn(
                'position',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'default' => '0'],
                'Position'
            )
            ->addColumn(
                'country_id',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Brand ID'
            )
            ->addIndex(
                $setup->getIdxName('smartosc_brand', ['name']),
                ['name']
            )
            ->addForeignKey(
                $setup->getFkName('smartosc_brand', 'country_id', 'directory_country', 'country_id'),
                'country_id',
                $setup->getTable('directory_country'),
                'country_id',
                Table::ACTION_CASCADE
            )
            ->setComment('Brand Information');
        $setup->getConnection()->createTable($table);


        $table = $setup->getConnection()
            ->newTable($setup->getTable('smartosc_brand_store'))
            ->addColumn(
                'brand_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Brand Id'
            )
            ->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Store Id'
            )
            ->addIndex(
                $setup->getIdxName('smartosc_brand_store', ['store_id']),
                ['store_id']
            )
            ->addForeignKey(
                $setup->getFkName('smartosc_brand_store', 'brand_id', 'smartosc_brand', 'brand_id'),
                'brand_id',
                $setup->getTable('smartosc_brand'),
                'brand_id',
                Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName('smartosc_brand_store', 'store_id', 'store', 'store_id'),
                'store_id',
                $setup->getTable('store'),
                'store_id',
                Table::ACTION_CASCADE
            )
            ->setComment('Brand Store');
        $setup->getConnection()->createTable($table);

        $table = $setup->getConnection()->newTable(
            $setup->getTable('smartosc_brand_product')
        )->addColumn(
            'brand_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true,'nullable' => false, 'primary' => true],
            'Brand ID'
        )->addColumn(
            'product_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true],
            'Product ID'
        )->addColumn(
            'position',
            Table::TYPE_INTEGER,
            11,
            ['nullable' => true],
            'Position'
        )->addForeignKey(
            $setup->getFkName('smartosc_brand_product', 'brand_id', 'smartosc_brand', 'brand_id'),
            'brand_id',
            $setup->getTable('smartosc_brand'),
            'brand_id',
            Table::ACTION_CASCADE
        )->addForeignKey(
            $setup->getFkName('smartosc_brand_product', 'product_id', 'catalog_product_entity', 'entity_id'),
            'product_id',
            $setup->getTable('catalog_product_entity'),
            'entity_id',
            Table::ACTION_CASCADE
        )->setComment(
            'Smartosc Brand To Product Linkage Table'
        );
        $setup->getConnection()->createTable($table);

        $setup->endSetup();
    }
}
