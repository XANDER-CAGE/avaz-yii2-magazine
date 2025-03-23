<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%category}}`.
 */
class m250323_145611_create_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('category', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->unique()->null(),
            'parent_id' => $this->integer()->null(),
            'status' => $this->boolean()->defaultValue(1),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk_category_parent',
            'category',
            'parent_id',
            'category',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_category_parent', 'category');
        $this->dropTable('category');
    }

}
