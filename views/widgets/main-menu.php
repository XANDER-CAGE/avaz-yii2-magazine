<?php
use yii\bootstrap5\Nav;
use yii\helpers\Url;

$menuItems = [
    ['label' => 'Главная', 'url' => ['/site/index']],
    ['label' => 'Каталог', 'url' => ['/product/index']],
    ['label' => 'Новинки', 'url' => ['/product/index', 'filter' => 'new']],
    ['label' => 'О нас', 'url' => ['/site/about']],
    ['label' => 'Контакты', 'url' => ['/site/contact']],
];
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainMenu">
            <?= Nav::widget([
                'options' => ['class' => 'navbar-nav mx-auto'],
                'items' => $menuItems,
            ]) ?>
        </div>
    </div>
</nav>
