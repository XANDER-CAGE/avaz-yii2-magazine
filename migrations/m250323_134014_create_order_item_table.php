<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order_item}}`.
 */
class m250323_134014_create_order_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('order_item', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull(),
            'price' => $this->decimal(10, 2)->notNull(),
        ]);

        $this->addForeignKey('fk_order_item_order', 'order_item', 'order_id', 'order', 'id', 'CASCADE');
        $this->addForeignKey('fk_order_item_product', 'order_item', 'product_id', 'product', 'id', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_order_item_order', 'order_item');
        $this->dropForeignKey('fk_order_item_product', 'order_item');
        $this->dropTable('order_item');
    }
}
