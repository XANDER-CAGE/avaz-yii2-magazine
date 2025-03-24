<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<header class="bg-dark text-white py-2 px-3 d-flex justify-content-between align-items-center">
    <div>Москва <i class="fas fa-chevron-down ml-1"></i></div>
    <div>
        <a href="<?= Url::to(['/site/delivery']) ?>" class="text-white mx-2">Доставка</a>
        <a href="<?= Url::to(['/site/payment']) ?>" class="text-white mx-2">Оплата</a>
        <a href="<?= Url::to(['/site/contact']) ?>" class="text-white mx-2">Контакты</a>
        <a href="<?= Url::to(['/site/about']) ?>" class="text-white mx-2">О компании</a>
    </div>
</header>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="<?= Url::home() ?>"><strong>limaron</strong></a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><?= Html::a('Женская одежда', ['/category/women'], ['class' => 'nav-link']) ?></li>
                <li class="nav-item"><?= Html::a('Мужская одежда', ['/category/men'], ['class' => 'nav-link']) ?></li>
                <li class="nav-item"><?= Html::a('Обувь', ['/category/shoes'], ['class' => 'nav-link']) ?></li>
                <li class="nav-item"><?= Html::a('Летняя коллекция', ['/category/summer'], ['class' => 'nav-link']) ?></li>
                <li class="nav-item"><?= Html::a('Акции', ['/promotion'], ['class' => 'nav-link text-danger']) ?></li>
            </ul>
            <div class="d-flex align-items-center">
                <a href="<?= Url::to(['/cart']) ?>" class="btn btn-light mx-1"><i class="fas fa-shopping-cart"></i></a>
                <a href="<?= Url::to(['/user/profile']) ?>" class="btn btn-light mx-1"><i class="far fa-user"></i></a>
                <a href="<?= Url::to(['/site/login']) ?>" class="btn btn-success mx-1">Войти</a>
            </div>
        </div>
    </div>
</nav>

<main class="container my-4">
    <?= Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <?= $content ?>
</main>

<section class="bg-light py-5">
    <div class="container text-center">
        <div class="row">
            <div class="col-md-3">
                <i class="fas fa-percent fa-2x text-success"></i>
                <h5 class="mt-2">Выгодные цены</h5>
                <p>Часто используемый в печати и веб-дизайне.</p>
            </div>
            <div class="col-md-3">
                <i class="fas fa-shield-alt fa-2x text-success"></i>
                <h5 class="mt-2">100% гарантия</h5>
                <p>Часто используемый в печати и веб-дизайне.</p>
            </div>
            <div class="col-md-3">
                <i class="fas fa-truck fa-2x text-success"></i>
                <h5 class="mt-2">Бесплатная доставка</h5>
                <p>Часто используемый в печати и веб-дизайне.</p>
            </div>
            <div class="col-md-3">
                <i class="fas fa-credit-card fa-2x text-success"></i>
                <h5 class="mt-2">Онлайн оплата</h5>
                <p>Часто используемый в печати и веб-дизайне.</p>
            </div>
        </div>
    </div>
</section>

<footer class="bg-dark text-white pt-4 pb-2">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h6>Карта сайта</h6>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-white">Доставка</a></li>
                    <li><a href="#" class="text-white">Оплата</a></li>
                    <li><a href="#" class="text-white">Контакты</a></li>
                    <li><a href="#" class="text-white">О компании</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6>Каталог</h6>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-white">Одежда и обувь</a></li>
                    <li><a href="#" class="text-white">Для дома</a></li>
                    <li><a href="#" class="text-white">Электроника</a></li>
                    <li><a href="#" class="text-white">Интернет</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6>Контакты</h6>
                <p>8 (800) 555 - 35 - 35</p>
                <p>shop@info.ru</p>
                <p>г. Москва, ул. Московская, д. 20</p>
            </div>
        </div>
        <div class="text-center mt-3">
            <small>© Все права защищены 2022</small>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
