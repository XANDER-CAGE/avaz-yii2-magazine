<?php

use yii\db\Migration;

class m250325_114306_add_name_to_order_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{%order_item}}', 'name', $this->string()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('{{%order_item}}', 'name');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250325_114306_add_name_to_order_item cannot be reverted.\n";

        return false;
    }
    */
}
