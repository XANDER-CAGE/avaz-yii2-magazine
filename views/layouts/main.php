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

<!-- Верхняя панель -->
<header class="bg-light border-bottom small py-2 px-3 d-flex justify-content-between align-items-center text-muted">
    <div>
        <i class="fas fa-map-marker-alt me-1"></i> Москва
    </div>
    <div class="d-none d-sm-block">
        <a href="<?= Url::to(['/site/delivery']) ?>" class="me-3">Доставка</a>
        <a href="<?= Url::to(['/site/payment']) ?>" class="me-3">Оплата</a>
        <a href="<?= Url::to(['/site/contact']) ?>" class="me-3">Контакты</a>
        <a href="<?= Url::to(['/site/about']) ?>">О компании</a>
    </div>
</header>

<!-- Навигация -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="<?= Url::home() ?>">Limaron</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php
                $topCategories = \app\models\Category::getTopCategories(5);
                foreach ($topCategories as $category): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Url::to(['/product/index', 'category_id' => $category->id]) ?>">
                            <?= Html::encode($category->name) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="<?= Url::to(['/site/sale']) ?>">Акции</a>
                </li>
            </ul>
            <div class="d-flex align-items-center">
                <a href="<?= Url::to(['/cart']) ?>" class="btn btn-light position-relative me-2">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?= Yii::$app->cart->getTotalCount() ?>
                    </span>
                </a>
                <a href="<?= Url::to(['/user/profile']) ?>" class="btn btn-light me-2">
                    <i class="far fa-user"></i>
                </a>
                <?php if (Yii::$app->user->isGuest): ?>
                    <a href="<?= Url::to(['/site/login']) ?>" class="btn btn-success">Войти</a>
                <?php else: ?>
                    <a href="<?= Url::to(['/site/logout']) ?>" class="btn btn-outline-secondary" data-method="post">Выйти</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Хлебные крошки -->
<div class="container my-3">
    <?= Breadcrumbs::widget([
        'links' => $this->params['breadcrumbs'] ?? [],
    ]) ?>
</div>

<!-- Контент -->
<main class="container mb-5">
    <?= $content ?>
</main>

<!-- Преимущества -->
<section class="bg-white py-5 border-top">
    <div class="container">
        <div class="row text-center">
            <div class="col-6 col-md-3 mb-4 mb-md-0">
                <i class="fas fa-percent fa-2x text-success mb-2"></i>
                <h6 class="fw-bold">Выгодные цены</h6>
                <small class="text-muted">Скидки каждый день</small>
            </div>
            <div class="col-6 col-md-3 mb-4 mb-md-0">
                <i class="fas fa-shield-alt fa-2x text-success mb-2"></i>
                <h6 class="fw-bold">Гарантия</h6>
                <small class="text-muted">Надёжность и возврат</small>
            </div>
            <div class="col-6 col-md-3 mb-4 mb-md-0">
                <i class="fas fa-truck fa-2x text-success mb-2"></i>
                <h6 class="fw-bold">Доставка</h6>
                <small class="text-muted">Быстро и удобно</small>
            </div>
            <div class="col-6 col-md-3">
                <i class="fas fa-credit-card fa-2x text-success mb-2"></i>
                <h6 class="fw-bold">Оплата онлайн</h6>
                <small class="text-muted">Карты и перевод</small>
            </div>
        </div>
    </div>
</section><!-- Подвал -->
<footer class="bg-dark text-white pt-4 pb-3">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-3">
                <h6>Компания</h6>
                <ul class="list-unstyled">
                    <li><a href="<?= Url::to(['/site/about']) ?>">О нас</a></li>
                    <li><a href="<?= Url::to(['/site/contact']) ?>">Контакты</a></li>
                    <li><a href="<?= Url::to(['/site/delivery']) ?>">Доставка</a></li>
                    <li><a href="<?= Url::to(['/site/payment']) ?>">Оплата</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-3">
                <h6>Каталог</h6>
                <ul class="list-unstyled">
                    <li><a href="<?= Url::to(['/product/index']) ?>">Все товары</a></li>
                    <li><a href="<?= Url::to(['/site/sale']) ?>">Скидки</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-3">
                <h6>Контакты</h6>
                <p class="mb-1">8 (800) 555-35-35</p>
                <p class="mb-1">shop@info.ru</p>
                <p class="mb-0">Москва, ул. Московская, д. 20</p>
            </div>
        </div>
        <div class="text-center mt-3">
            <small class="text-muted">© <?= date('Y') ?> Limaron. Все права защищены.</small>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>