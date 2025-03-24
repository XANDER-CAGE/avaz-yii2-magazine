<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\CartIconWidget;
?>

<header class="site-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-3">
                <a href="<?= Yii::$app->homeUrl ?>" class="site-logo">
                    STORE
                </a>
            </div>
            <div class="col-md-6">
                <form class="search-form" action="<?= Url::to(['/product/search']) ?>" method="get" role="search">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" placeholder="Поиск товаров..." aria-label="Поиск товаров">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-3 text-end">
                <div class="d-flex justify-content-end align-items-center">
                    <?php if (Yii::$app->user->isGuest): ?>
                        <a href="<?= Url::to(['/site/login']) ?>" class="btn btn-link" aria-label="Вход">
                            <i class="fas fa-user"></i>
                        </a>
                    <?php else: ?>
                        <div class="dropdown">
                            <a href="#" class="btn btn-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Профиль">
                                <i class="fas fa-user"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="<?= Url::to(['/profile/index']) ?>">Профиль</a>
                                <a class="dropdown-item" href="<?= Url::to(['/order/history']) ?>">Заказы</a>
                                <div class="dropdown-divider"></div>
                                <?= Html::beginForm(['/site/logout'], 'post', ['class' => 'dropdown-item p-0']) .
                                    Html::submitButton('Выйти', ['class' => 'btn btn-link dropdown-item text-left w-100']) .
                                    Html::endForm() ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?= CartIconWidget::widget() ?>
                </div>
            </div>
        </div>
    </div>
</header>
