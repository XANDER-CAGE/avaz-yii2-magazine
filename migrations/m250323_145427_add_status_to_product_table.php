<?php

use yii\db\Migration;

class m250323_145427_add_status_to_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('product', 'status', $this->boolean()->defaultValue(1)->after('category_id'));
    }
    
    public function safeDown()
    {
        $this->dropColumn('product', 'status');
    }
    

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250323_145427_add_status_to_product_table cannot be reverted.\n";

        return false;
    }
    */
}
