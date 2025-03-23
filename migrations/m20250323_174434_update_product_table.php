<?php

use yii\db\Migration;

/**
 * Updates table `product` with additional fields.
 */
class m20250323_174434_update_product_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('product', 'slug', $this->string()->unique()->null()->after('name'));
        $this->addColumn('product', 'image', $this->string()->null()->after('price'));
        $this->addColumn('product', 'status', $this->boolean()->defaultValue(1)->after('category_id'));
    }

    public function safeDown()
    {
        $this->dropColumn('product', 'slug');
        $this->dropColumn('product', 'image');
        $this->dropColumn('product', 'status');
    }
}
