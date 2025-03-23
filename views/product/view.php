<?php
use yii\helpers\Html;

/** @var $product \app\models\Product */
?>

<h1><?= Html::encode($product->name) ?></h1>

<p><strong>Цена:</strong> <?= $product->price ?> ₽</p>

<p><?= $product->full_description ?></p>

<p>
    <?= \yii\helpers\Html::a('Добавить в корзину', ['cart/add', 'id' => $product->id], ['class' => 'btn btn-success']) ?>
</p>
