<?php

use yii\db\Migration;

class m250323_145501_add_image_to_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('product', 'image', $this->string()->null()->after('price'));
    }
    
    public function safeDown()
    {
        $this->dropColumn('product', 'image');
    }
    

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250323_145501_add_image_to_product_table cannot be reverted.\n";

        return false;
    }
    */
}
