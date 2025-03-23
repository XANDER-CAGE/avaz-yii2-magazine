<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class OrderLog extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%order_log}}';
    }

    public function rules()
    {
        return [
            [['order_id', 'action'], 'required'],
            [['order_id', 'user_id'], 'integer'],
            [['comment'], 'string'],
            [['created_at'], 'safe'],
            [['action'], 'string', 'max' => 255],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }

    public static function add($orderId, $action, $comment = null)
    {
        $log = new self();
        $log->order_id = $orderId;
        $log->user_id = Yii::$app->user->id ?? null;
        $log->action = $action;
        $log->comment = $comment;
        $log->save(false);
    }

}
