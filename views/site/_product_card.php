<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var \app\models\Product $product */
?>

<div class="product-card h-100 border rounded overflow-hidden shadow-sm">
    <div class="product-image position-relative" style="padding-top: 120%;">
        <img src="<?= $product->image ?: '/img/no-image.jpg' ?>" alt="<?= Html::encode($product->name) ?>" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover">
        <?php if ($product->price > 0): ?>
            <div class="product-discount position-absolute top-0 end-0 bg-danger text-white px-2 py-1 small">-30%</div>
        <?php endif; ?>
    </div>
    <div class="product-info p-3">
        <div class="product-category text-muted small mb-1">
            <?= $product->category ? Html::encode($product->category->name) : '' ?>
        </div>
        <h6 class="product-title mb-2">
            <a href="<?= Url::to(['product/view', 'id' => $product->id]) ?>" class="text-dark">
                <?= Html::encode($product->name) ?>
            </a>
        </h6>
        <div class="product-price">
            <?php if ($product->price): ?>
                <span class="fw-bold me-2"><?= number_format($product->price * 0.7, 0, '.', ' ') ?> руб.</span>
                <span class="text-muted text-decoration-line-through"><?= number_format($product->price, 0, '.', ' ') ?> руб.</span>
            <?php else: ?>
                <span class="text-muted">Цена по запросу</span>
            <?php endif; ?>
        </div>
    </div>
</div>
