<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= \yii\helpers\Url::to(['/admin']) ?>" class="brand-link">
        <img src="<?= $assetDir ?>/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Панель управления</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- User panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= $assetDir ?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">
                    <?= Yii::$app->user->identity->username ?? 'Admin' ?>
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <?= \hail812\adminlte\widgets\Menu::widget([
                'items' => [
                    ['label' => 'Дашборд', 'url' => ['/admin'], 'icon' => 'tachometer-alt'],

                    ['label' => 'Каталог', 'header' => true],
                    ['label' => 'Товары', 'url' => ['/admin/product'], 'icon' => 'box'],
                    ['label' => 'Категории', 'url' => ['/admin/category'], 'icon' => 'tags'],

                    ['label' => 'Заказы и пользователи', 'header' => true],
                    ['label' => 'Заказы', 'url' => ['/admin/order'], 'icon' => 'shopping-cart'],
                    ['label' => 'Пользователи', 'url' => ['/admin/user'], 'icon' => 'users'],

                    ['label' => 'Системное', 'header' => true],
                    ['label' => 'Gii', 'url' => ['/gii'], 'icon' => 'code', 'target' => '_blank'],
                    ['label' => 'Debug', 'url' => ['/debug'], 'icon' => 'bug', 'target' => '_blank'],
                    ['label' => 'Выход', 'url' => ['/site/logout'], 'icon' => 'sign-out-alt', 'linkOptions' => ['data-method' => 'post']],
                ]
            ]) ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
