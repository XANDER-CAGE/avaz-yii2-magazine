<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property float $total
 * @property string $status
 * @property string $created_at
 *
 * @property OrderItem[] $items
 * @property User|null $user
 */
class Order extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%order}}';
    }

    public function rules()
    {
        return [
            [['name', 'email', 'phone', 'address'], 'required'],
            [['user_id'], 'integer'],
            [['total'], 'number'],
            [['created_at'], 'safe'],
            [['admin_comment'], 'string'],
            [['name', 'email', 'phone', 'address', 'status'], 'string', 'max' => 255],
            ['email', 'email'],
        ];
    }
    

    public function getItems()
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
