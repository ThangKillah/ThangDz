<?php
namespace ThangDz\Blog\Setup;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{

    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup,
                            \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $table = $installer->getConnection()->newTable(
                $installer->getTable('cowell_blog_post')
            )
                ->addColumn(
                    'post_id',Table::TYPE_SMALLINT, null,
                    [
                        'identity' => true,
                        'nullable' => false,
                        'primary'  => true,
                        'unsigned' => true,
                    ],
                    'Post ID'
                )
                ->addColumn(
                    'title',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable => false'],
                    'Post Title'
                )
                ->addColumn(
                    'url_key',
                    Table::TYPE_TEXT,
                    255,
                    [],
                    'Post URL Key'
                )
                ->addColumn(
                    'content',
                   Table::TYPE_TEXT,
                    '64k',
                    [],
                    'Post Content'
                )
                ->addColumn(
                    'status',
                    Table::TYPE_INTEGER,
                    1,
                    [],
                    'Is Post Active ?'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                    'Created At'
                )->addColumn(
                    'updated_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' =>  Table::TIMESTAMP_INIT_UPDATE],
                    'Updated At')
                ->addIndex(
                    $setup->getIdxName(
                        $installer->getTable('cowell_blog_post'),
                        ['title'],
                        AdapterInterface::INDEX_TYPE_FULLTEXT
                    ),
                    ['title'],
                    ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
                )
                ->setComment('Blog Post');
            $installer->getConnection()->createTable($table);
            $installer->endSetup();
    }
}
