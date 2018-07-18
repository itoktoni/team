<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product`.
 */
class m180705_060243_create_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('product', [
            'id' => $this->primaryKey(),
            'slug' => $this->string()->notNull(),
            'name' => $this->string(64)->notNull(),
            'category' => $this->integer(64)->notNull(),
            'synopsis' => $this->string(),
            'description' => $this->text(),
            'price' => $this->decimal(),
            'price_discount' => $this->decimal(),
            'brand' => $this->integer()->notNull(),
            'discount_flag' => $this->tinyInteger(1)->defaultValue(1),
            'image' => $this->string(),
            'image_path' => $this->string(),
            'image_thumbnail' => $this->string(),
            'image_portrait' => $this->string(),
            'headline' => $this->boolean(),
            'meta_description' => $this->string(),
            'meta_keyword' => $this->string(),
            'product_download_url' => $this->string(),
            'product_download_path' => $this->string(),
            'product_view' => $this->integer(),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue(1),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->createTable('product_content', [
            'id' => $this->primaryKey(),
            'product' => $this->integer(),
            'embed_type' => $this->tinyinteger(1)->notNull(),
            'content_type' => $this->tinyinteger(1)->notNull(),
            'content' => $this->string(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue(1),
        ], $tableOptions);

        $this->createTable('product_category', [
            'id' => $this->primaryKey(),
            'product' => $this->integer()->notNull(),
            'sub_category' => $this->integer()->notNull(),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue(0),
        ]);

        // $this->addPrimaryKey('prod-cate_pk', 'product_category', ['product', 'sub_category']);

        $this->createTable('brand', [
            'id' => $this->primaryKey(),
            'slug' => $this->string()->notNull(),
            'name' => $this->string(),
            'description' => $this->string(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue(1),
        ], $tableOptions);

        $this->createTable('category', [
            'id' => $this->primaryKey(),
            'slug' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'description' => $this->string(),
            'image' => $this->string(),
            'image_path' => $this->string(),
            'image_thumbnail' => $this->string(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue(1),
        ], $tableOptions);

        $this->createTable('sub_category', [
            'id' => $this->primaryKey(),
            'category' => $this->integer(),
            'slug' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'description' => $this->string(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue(1),
        ], $tableOptions);

        $this->addForeignKey(
            'fk-subcategory-category', 'sub_category', 'category', 'category', 'id', 'CASCADE'
        );
        $this->addForeignKey(
            'fk-product-brand', 'product', 'brand', 'brand', 'id', 'CASCADE'
        );

        $this->addForeignKey(
            'fk-product-category', 'product', 'category', 'category', 'id', 'CASCADE'
        );

        $this->addForeignKey(
            'fk-product-content', 'product_content', 'product', 'product', 'id', 'CASCADE'
        );

        $this->addForeignKey(
            'fk-product-category-product', 'product_category', 'product', 'product', 'id', 'CASCADE'
        );

        $this->addForeignKey(
            'fk-product-sub-category', 'product_category', 'sub_category', 'sub_category', 'id', 'CASCADE'
        );

        $rows = [
            [1, 'marvel', 'Marvel', 'Marvel', 1],
            [2, 'universal-studio', 'Universal Studio', 'Universal Studio Brand', 1],
            [3, 'pixar', 'Pixar', 'Pixar Brand', 1],
        ];

        $this->batchInsert('brand', [
            'id',
            'slug',
            'name',
            'description',
            'status'],
            $rows);

        $rows = [
            [1, 'movie', 'Movie', 'Movie Category', 1],
            [2, 'application', 'Apps', 'Application Category', 1],
            [3, 'music', 'Music', 'Music Category', 1],
        ];

        $this->batchInsert('category', [
            'id',
            'slug',
            'name',
            'description',
            'status'],
            $rows);

        $rows = [
            [1,'fantasy', 'Fantasy', 'Fantasy Subcategory', 1],
            [1,'adventure', 'Adventure', 'Adventure Subcategory', 1],
            [1,'horror', 'Horror', 'Horror Subcategory', 1],
            [2,'games', 'Games', 'Games Subcategory', 1],
            [2,'utility', 'Utility', 'Utility Subcategory', 1],
            [2,'scheduler', 'Scheduler', 'Scheduler Subcategory', 1],
            [3,'rock', 'Rock', 'Rock Subcategory', 1],
            [3,'pop', 'Pop', 'Pop Subcategory', 1],
            [3,'blues', 'Blues', 'Blues Subcategory', 1],
        ];

        $this->batchInsert('sub_category', [
            'category',
            'slug',
            'name',
            'description',
            'status'],
            $rows);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('product_content');
        $this->dropTable('product');
        $this->dropTable('brand');
        $this->dropTable('category');
        $this->dropTable('sub_category');
    }
}
