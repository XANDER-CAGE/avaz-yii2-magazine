<?php

namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class CartIconWidget extends Widget
{
    public function run()
    {
        $cartCount = 0;

        if (isset(Yii::$app->cart) && method_exists(Yii::$app->cart, 'getItems')) {
            $cartCount = count(Yii::$app->cart->getItems());
        }

        return Html::a(
            Html::tag('div',
                Html::tag('i', '', ['class' => 'fas fa-shopping-bag']) .
                ($cartCount > 0 ? Html::tag('span', $cartCount, ['class' => 'cart-count']) : ''),
                ['class' => 'cart-icon']
            ),
            Url::to(['/cart/index']),
            ['class' => 'btn btn-link ms-2']
        );
    }
}
