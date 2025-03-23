<?php

use yii\db\Migration;

class m250323_143644_add_admin_comment_to_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%order}}', 'admin_comment', $this->text()->null());
    }
    
    public function safeDown()
    {
        $this->dropColumn('{{%order}}', 'admin_comment');
    }
    

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250323_143644_add_admin_comment_to_order cannot be reverted.\n";

        return false;
    }
    */
}
