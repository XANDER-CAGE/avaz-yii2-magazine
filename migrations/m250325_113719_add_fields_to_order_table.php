<?php

use yii\db\Migration;

class m250325_113719_add_fields_to_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('{{%order}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'name' => $this->string()->notNull(),
            'email' => $this->string()->defaultValue(null),
            'phone' => $this->string()->notNull(),
            'address' => $this->text(),
            'total' => $this->decimal(10,2)->notNull()->defaultValue(0),
            'status' => $this->string()->notNull()->defaultValue('pending'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'delivery_method' => $this->string()->defaultValue(null),
            'payment_method' => $this->string()->defaultValue(null),
            'comment' => $this->text()->defaultValue(null),
            'admin_comment' => $this->text()->defaultValue(null),
        ]);

        $this->addForeignKey(
            'fk-order-user_id',
            '{{%order}}',
            'user_id',
            '{{%user}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk-order-user_id', '{{%order}}');

        $this->dropColumn('{{%order}}', 'user_id');
        $this->dropColumn('{{%order}}', 'name');
        $this->dropColumn('{{%order}}', 'email');
        $this->dropColumn('{{%order}}', 'phone');
        $this->dropColumn('{{%order}}', 'address');
        $this->dropColumn('{{%order}}', 'total');
        $this->dropColumn('{{%order}}', 'status');
        $this->dropColumn('{{%order}}', 'created_at');
        $this->dropColumn('{{%order}}', 'delivery_method');
        $this->dropColumn('{{%order}}', 'payment_method');
        $this->dropColumn('{{%order}}', 'comment');
        $this->dropColumn('{{%order}}', 'admin_comment');
    }
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250325_113719_add_fields_to_order_table cannot be reverted.\n";

        return false;
    }
    */
}
