<?php

namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Виджет для отображения мини-корзины в виде всплывающего окна
 */
class CartWidget extends Widget
{
    /**
     * @var string CSS-класс контейнера
     */
    public $containerClass = 'mini-cart-container';
    
    /**
     * @var int Максимальное количество товаров для отображения
     */
    public $maxItems = 3;
    
    /**
     * @var string Текст для ссылки "Перейти в корзину"
     */
    public $viewCartText = 'Перейти в корзину';
    
    /**
     * @var string Текст для ссылки "Оформить заказ"
     */
    public $checkoutText = 'Оформить заказ';
    
    /**
     * @var string Текст для пустой корзины
     */
    public $emptyCartText = 'Ваша корзина пуста';
    
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $items = [];
        $total = 0;
        
        if (isset(Yii::$app->cart) && method_exists(Yii::$app->cart, 'getProductData')) {
            $items = Yii::$app->cart->getProductData();
            $total = Yii::$app->cart->getTotalSum();
        }
        
        return $this->render('mini-cart', [
            'items' => $items,
            'total' => $total,
            'maxItems' => $this->maxItems,
            'containerClass' => $this->containerClass,
            'viewCartText' => $this->viewCartText,
            'checkoutText' => $this->checkoutText,
            'emptyCartText' => $this->emptyCartText,
        ]);
    }
}