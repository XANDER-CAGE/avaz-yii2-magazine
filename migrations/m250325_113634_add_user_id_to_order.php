<?php

use yii\db\Migration;

class m250325_113634_add_user_id_to_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{%order}}', 'user_id', $this->integer());
    
        // если у тебя есть таблица user, добавим внешний ключ
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
    }
    
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250325_113634_add_user_id_to_order cannot be reverted.\n";

        return false;
    }
    */
}
