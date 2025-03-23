<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property string $name
 * @property float $price
 * @property int $quantity
 * @property float $sum
 *
 * @property Order $order
 * @property Product $product
 */
class OrderItem extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%order_item}}';
    }

    public function rules()
    {
        return [
            [['order_id', 'product_id', 'name', 'price', 'quantity', 'sum'], 'required'],
            [['order_id', 'product_id', 'quantity'], 'integer'],
            [['price', 'sum'], 'number'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }

    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }
}
