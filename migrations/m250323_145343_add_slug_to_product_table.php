<?php

use yii\db\Migration;

class m250323_145343_add_slug_to_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('product', 'slug', $this->string()->unique()->null()->after('name'));
    }
    
    public function safeDown()
    {
        $this->dropColumn('product', 'slug');
    }
    

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250323_145343_add_slug_to_product_table cannot be reverted.\n";

        return false;
    }
    */
}
