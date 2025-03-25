<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%order}}`.
 */
class m250325_113555_add_total_column_to_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{%order}}', 'total', $this->decimal(10,2)->notNull()->defaultValue(0));
    }
    
    public function down()
    {
        $this->dropColumn('{{%order}}', 'total');
    }
    
}
