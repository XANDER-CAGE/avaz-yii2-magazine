<?php

namespace app\components;

use Yii;
use app\models\Product;

class CartComponent extends \yii\base\Component
{
    const SESSION_KEY = 'cart';

    public function getItems()
    {
        return Yii::$app->session->get(self::SESSION_KEY, []);
    }

    public function add($productId, $quantity = 1)
    {
        $cart = $this->getItems();

        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        Yii::$app->session->set(self::SESSION_KEY, $cart);
    }

    public function remove($productId)
    {
        $cart = $this->getItems();

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Yii::$app->session->set(self::SESSION_KEY, $cart);
        }
    }

    public function clear()
    {
        Yii::$app->session->remove(self::SESSION_KEY);
    }

    public function getTotalCount()
    {
        return array_sum($this->getItems());
    }

    public function getTotalSum()
    {
        $sum = 0;
        foreach ($this->getItems() as $productId => $qty) {
            $product = Product::findOne($productId);
            if ($product) {
                $sum += $product->price * $qty;
            }
        }
        return $sum;
    }

    public function getProductData()
    {
        $items = [];
        foreach ($this->getItems() as $productId => $qty) {
            $product = Product::findOne($productId);
            if ($product) {
                $items[] = [
                    'product' => $product,
                    'quantity' => $qty,
                    'sum' => $product->price * $qty
                ];
            }
        }
        return $items;
    }
}
