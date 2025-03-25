<?php

use yii\db\Migration;

class m250325_114434_add_sum_to_order_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{%order_item}}', 'sum', $this->decimal(10, 2)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('{{%order_item}}', 'sum');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250325_114434_add_sum_to_order_item cannot be reverted.\n";

        return false;
    }
    */
}
