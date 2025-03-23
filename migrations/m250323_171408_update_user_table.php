<?php

use yii\db\Migration;

class m250323_171408_update_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Проверяем, существуют ли уже нужные поля
        $tableSchema = $this->db->getTableSchema('user');
        
        // Добавление новых полей в таблицу user
        if (!isset($tableSchema->columns['email'])) {
            $this->addColumn('user', 'email', $this->string()->notNull()->after('username'));
        }
        
        if (!isset($tableSchema->columns['status'])) {
            $this->addColumn('user', 'status', $this->smallInteger()->notNull()->defaultValue(10)->after('auth_key'));
        }
        
        if (!isset($tableSchema->columns['role'])) {
            $this->addColumn('user', 'role', $this->string(20)->notNull()->defaultValue('user')->after('status'));
        }
        
        if (!isset($tableSchema->columns['first_name'])) {
            $this->addColumn('user', 'first_name', $this->string()->after('role'));
        }
        
        if (!isset($tableSchema->columns['last_name'])) {
            $this->addColumn('user', 'last_name', $this->string()->after('first_name'));
        }
        
        if (!isset($tableSchema->columns['phone'])) {
            $this->addColumn('user', 'phone', $this->string(20)->after('last_name'));
        }
        
        if (!isset($tableSchema->columns['avatar'])) {
            $this->addColumn('user', 'avatar', $this->string()->after('phone'));
        }
        
        if (!isset($tableSchema->columns['verification_token'])) {
            $this->addColumn('user', 'verification_token', $this->string()->after('avatar'));
        }
        
        if (!isset($tableSchema->columns['password_reset_token'])) {
            $this->addColumn('user', 'password_reset_token', $this->string()->after('verification_token'));
        }
        
        if (!isset($tableSchema->columns['created_at'])) {
            $this->addColumn('user', 'created_at', $this->dateTime()->after('password_reset_token'));
        }
        
        if (!isset($tableSchema->columns['updated_at'])) {
            $this->addColumn('user', 'updated_at', $this->dateTime()->after('created_at'));
        }

        // Создаем уникальные индексы
        $this->createIndex('idx_user_username', 'user', 'username', true);
        $this->createIndex('idx_user_email', 'user', 'email', true);
        $this->createIndex('idx_user_password_reset_token', 'user', 'password_reset_token', true);
        
        // Обновляем существующих пользователей, если они есть
        $this->execute("
            UPDATE user 
            SET status = 10, 
                role = CASE 
                    WHEN is_admin = 1 THEN 'admin' 
                    ELSE 'user' 
                END,
                email = CONCAT(username, '@example.com'),
                created_at = NOW(),
                updated_at = NOW()
            WHERE email IS NULL OR email = ''
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Удаление добавленных полей в обратном порядке
        $this->dropIndex('idx_user_password_reset_token', 'user');
        $this->dropIndex('idx_user_email', 'user');
        $this->dropIndex('idx_user_username', 'user');
        
        $this->dropColumn('user', 'updated_at');
        $this->dropColumn('user', 'created_at');
        $this->dropColumn('user', 'password_reset_token');
        $this->dropColumn('user', 'verification_token');
        $this->dropColumn('user', 'avatar');
        $this->dropColumn('user', 'phone');
        $this->dropColumn('user', 'last_name');
        $this->dropColumn('user', 'first_name');
        $this->dropColumn('user', 'role');
        $this->dropColumn('user', 'status');
        $this->dropColumn('user', 'email');
    }
}