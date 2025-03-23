<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product`.
 */
class m20250324_000001_create_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Удаляем старую таблицу, если она существует
        $this->dropTableIfExists('product');
        
        // Создаем новую таблицу
        $this->createTable('product', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull()->comment('Название товара'),
            'slug' => $this->string()->unique()->comment('URL-дружественное название'),
            'sku' => $this->string()->comment('Артикул'),
            'price' => $this->decimal(10, 2)->notNull()->defaultValue(0),
            'short_description' => $this->string()->comment('Краткое описание'),
            'full_description' => $this->text()->comment('Полное описание'),
            'image' => $this->string()->comment('Путь к изображению'),
            'status' => $this->boolean()->defaultValue(1)->comment('Статус товара (1 - активен, 0 - неактивен)'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
        
        // Добавляем индексы
        $this->createIndex('idx-product-category_id', 'product', 'category_id');
        $this->createIndex('idx-product-slug', 'product', 'slug');
        $this->createIndex('idx-product-sku', 'product', 'sku');
        $this->createIndex('idx-product-status', 'product', 'status');
        
        // Добавляем внешний ключ
        $this->addForeignKey(
            'fk-product-category_id',
            'product',
            'category_id',
            'category',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Удаляем внешний ключ
        $this->dropForeignKey('fk-product-category_id', 'product');
        
        // Удаляем таблицу
        $this->dropTable('product');
    }
    
    /**
     * Удаляет таблицу только если она существует
     * 
     * @param string $table Имя таблицы
     */
    protected function dropTableIfExists($table)
    {
        $tableSchema = $this->db->schema->getTableSchema($table);
        if ($tableSchema !== null) {
            $this->dropTable($table);
        }
    }
}