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
 * @property string|null $delivery_method
 * @property string|null $payment_method
 * @property string|null $comment
 * @property string|null $admin_comment
 *
 * @property OrderItem[] $items
 * @property User|null $user
 */
class Order extends ActiveRecord
{
    // Константы для статусов заказа
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'done';
    const STATUS_CANCELLED = 'cancelled';

    public static function tableName()
    {
        return '{{%order}}';
    }

    public function rules()
    {
        return [
            [['phone'], 'required', 'message' => 'Пожалуйста, укажите ваш номер телефона'],
            [['user_id'], 'integer'],
            [['total'], 'number'],
            [['address', 'comment', 'admin_comment'], 'string'],
            [['created_at'], 'safe'],
            [['name', 'email', 'phone', 'status', 'delivery_method', 'payment_method'], 'string', 'max' => 255],
            ['email', 'email'],
            ['status', 'default', 'value' => self::STATUS_PENDING],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'name' => 'Имя',
            'email' => 'Email',
            'phone' => 'Телефон',
            'address' => 'Адрес доставки',
            'total' => 'Сумма заказа',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'delivery_method' => 'Способ доставки',
            'payment_method' => 'Способ оплаты',
            'comment' => 'Комментарий к заказу',
            'admin_comment' => 'Комментарий администратора',
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

    /**
     * Получить список статусов заказа
     * @return array
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_PENDING => 'Ожидает обработки',
            self::STATUS_PROCESSING => 'В обработке',
            self::STATUS_SHIPPED => 'Отправлен',
            self::STATUS_DELIVERED => 'Доставлен',
            self::STATUS_CANCELLED => 'Отменен',
        ];
    }

    /**
     * Получить название статуса заказа
     * @return string
     */
    public function getStatusName()
    {
        $statusList = self::getStatusList();
        return isset($statusList[$this->status]) ? $statusList[$this->status] : $this->status;
    }

    /**
     * Заполнить данные о пользователе если он авторизован
     */
    public function fillUserData()
    {
        if (!Yii::$app->user->isGuest) {
            $user = Yii::$app->user->identity;
            $this->user_id = $user->id;
            $this->name = $user->getFullName() ?: $user->username;
            $this->email = $user->email;
            
            // Если у пользователя заполнен телефон, используем его
            if ($user->phone && empty($this->phone)) {
                $this->phone = $user->phone;
            }
        }
    }
}