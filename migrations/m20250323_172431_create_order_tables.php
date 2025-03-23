<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order` and `order_item`.
 */
class m20250323_172431_create_order_tables extends Migration
{
    public function safeUp()
    {
        // Таблица заказов
        $this->createTable('{{%order}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->null(),
            'name' => $this->string()->notNull(),
            'email' => $this->string()->notNull(),
            'phone' => $this->string()->notNull(),
            'address' => $this->string()->notNull(),
            'total' => $this->decimal(10,2)->notNull(),
            'status' => $this->string()->defaultValue('pending'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->createIndex('idx-order-user_id', '{{%order}}', 'user_id');
        $this->addForeignKey(
            'fk-order-user_id',
            '{{%order}}',
            'user_id',
            '{{%user}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        // Таблица товаров в заказе
        $this->createTable('{{%order_item}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'price' => $this->decimal(10,2)->notNull(),
            'quantity' => $this->integer()->notNull(),
            'sum' => $this->decimal(10,2)->notNull(),
        ]);

        $this->createIndex('idx-order_item-order_id', '{{%order_item}}', 'order_id');
        $this->createIndex('idx-order_item-product_id', '{{%order_item}}', 'product_id');

        $this->addForeignKey(
            'fk-order_item-order_id',
            '{{%order_item}}',
            'order_id',
            '{{%order}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-order_item-product_id',
            '{{%order_item}}',
            'product_id',
            '{{%product}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-order_item-product_id', '{{%order_item}}');
        $this->dropForeignKey('fk-order_item-order_id', '{{%order_item}}');
        $this->dropTable('{{%order_item}}');

        $this->dropForeignKey('fk-order-user_id', '{{%order}}');
        $this->dropTable('{{%order}}');
    }
}
