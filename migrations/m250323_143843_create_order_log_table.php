<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order_log}}`.
 */
class m250323_143843_create_order_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%order_log}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->null(),
            'action' => $this->string()->notNull(),
            'comment' => $this->text(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    
        $this->addForeignKey('fk_order_log_order', '{{%order_log}}', 'order_id', '{{%order}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_order_log_user', '{{%order_log}}', 'user_id', '{{%user}}', 'id', 'SET NULL');
    }
    
    public function safeDown()
    {
        $this->dropForeignKey('fk_order_log_user', '{{%order_log}}');
        $this->dropForeignKey('fk_order_log_order', '{{%order_log}}');
        $this->dropTable('{{%order_log}}');
    }
    
}
