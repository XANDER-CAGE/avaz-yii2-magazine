<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var $products \app\models\Product[] */
/** @var $categories \app\models\Category[] */
?>

<h1>Каталог товаров</h1>

<div>
    <strong>Категории:</strong>
    <ul>
        <li><?= Html::a("Все", ['product/index']) ?></li>
        <?php foreach ($categories as $category): ?>
            <li>
                <?= Html::a($category->name, ['product/index', 'category_id' => $category->id]) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<hr>

<div>
    <?php foreach ($products as $product): ?>
        <div style="border:1px solid #ccc; padding:10px; margin:10px 0;">
            <h3><?= Html::a($product->name, ['product/view', 'id' => $product->id]) ?></h3>
            <p><strong>Цена:</strong> <?= $product->price ?> ₽</p>
            <p><?= $product->short_description ?></p>
        </div>
    <?php endforeach; ?>
</div>
