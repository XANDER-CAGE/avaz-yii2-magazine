<?php
/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Интернет-магазин одежды';

// Товары
$saleProducts = \app\models\Product::find()->limit(4)->all();
$popularProducts = \app\models\Product::find()->orderBy(['id' => SORT_DESC])->limit(4)->all();
$catalogProducts = \app\models\Product::find()->limit(9)->all();
?>




<!-- КАТАЛОГ -->
<div class="catalog-section mb-5">
    <h2 class="section-title mb-4">Каталог</h2>
    <div class="row">
        <!-- Фильтры -->
        <div class="col-md-3">
            <div class="filters-sidebar p-3 bg-light rounded">
                <h5>Категории</h5>
                <ul class="list-unstyled">
                    <li><label><input type="checkbox"> Ботинки (936)</label></li>
                    <li><label><input type="checkbox"> Кроссовки (2380)</label></li>
                    <li><label><input type="checkbox"> Мокасины (501)</label></li>
                    <li><label><input type="checkbox"> Туфли (1260)</label></li>
                    <li><a href="#">Показать все</a></li>
                </ul>

                <h5 class="mt-4">Цена</h5>
                <div class="d-flex mb-3">
                    <input type="number" class="form-control me-2" placeholder="от 990 ₽">
                    <input type="number" class="form-control" placeholder="до 99 990 ₽">
                </div>

                <h5>Бренды</h5>
                <ul class="list-unstyled">
                    <li><label><input type="checkbox"> New Balance (637)</label></li>
                    <li><label><input type="checkbox"> Nike (383)</label></li>
                    <li><label><input type="checkbox"> Balenciaga (250)</label></li>
                    <li><label><input type="checkbox"> Converse (150)</label></li>
                    <li><a href="#">Показать все</a></li>
                </ul>

                <div class="mt-4 d-grid gap-2">
                    <button class="btn btn-outline-secondary">Сбросить фильтр</button>
                    <button class="btn btn-success">Применить</button>
                </div>
            </div>
        </div>

        <!-- Товары -->
        <div class="col-md-9">
            <div class="row">
                <?php foreach ($catalogProducts as $product): ?>
                    <div class="col-md-4 mb-4">
                        <?= $this->render('_product_card', ['product' => $product]) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

