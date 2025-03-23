<?php
use yii\helpers\Html;
use yii\helpers\Url;

$assetUrl = $assetDir ?? Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
$user = Yii::$app->user->identity ?? null;
?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Левое меню -->
    <ul class="navbar-nav">
        <!-- Бургер -->
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <!-- На сайт -->
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?= Url::to(['/']) ?>" class="nav-link" target="_blank"><i class="fas fa-store"></i> На сайт</a>
        </li>
        <!-- Админка -->
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?= Url::to(['/admin']) ?>" class="nav-link"><i class="fas fa-tachometer-alt"></i> Панель</a>
        </li>
    </ul>

    <!-- Поиск -->
    <form class="form-inline ml-3" method="get" action="<?= Url::to(['/admin/product']) ?>">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" name="ProductSearch[name]" placeholder="Поиск товара..." aria-label="Поиск">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </form>

    <!-- Правое меню -->
    <ul class="navbar-nav ml-auto">

        <!-- Полный экран -->
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#"><i class="fas fa-expand-arrows-alt"></i></a>
        </li>

        <!-- Аккаунт -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button">
                <i class="fas fa-user-circle"></i> <?= $user ? Html::encode($user->username) : 'Гость' ?>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <?php if ($user): ?>
                    <?= Html::a('<i class="fas fa-user-cog mr-2"></i> Профиль', ['/admin/user/update', 'id' => $user->id], ['class' => 'dropdown-item']) ?>
                    <div class="dropdown-divider"></div>
                    <?= Html::a('<i class="fas fa-sign-out-alt mr-2"></i> Выход', ['/site/logout'], [
                        'class' => 'dropdown-item text-danger',
                        'data-method' => 'post'
                    ]) ?>
                <?php else: ?>
                    <?= Html::a('<i class="fas fa-sign-in-alt mr-2"></i> Войти', ['/site/login'], ['class' => 'dropdown-item']) ?>
                <?php endif; ?>
            </div>
        </li>

        <!-- Настройки боковой панели -->
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#">
                <i class="fas fa-th-large"></i>
            </a>
        </li>
    </ul>
</nav>
