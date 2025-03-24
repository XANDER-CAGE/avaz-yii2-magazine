<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var \app\models\Category[] $categories */
$activeCategory = Yii::$app->request->get('category_id');
?>

<div class="category-menu">
    <div class="container">
        <nav class="nav justify-content-center">
            <a class="nav-link <?= $activeCategory === null ? 'active' : '' ?>" href="<?= Url::to(['/product/index']) ?>">Все</a>
            <?php foreach ($categories as $category): ?>
                <a class="nav-link <?= $activeCategory == $category->id ? 'active' : '' ?>" href="<?= Url::to(['/product/index', 'category_id' => $category->id]) ?>">
                    <?= Html::encode($category->name) ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </div>
</div>
