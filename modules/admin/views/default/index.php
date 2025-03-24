<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Product;
use app\models\Category;
use app\models\Order;
use app\models\User;

/* @var $this yii\web\View */

$this->title = 'Панель управления';
$this->params['breadcrumbs'][] = $this->title;

// Статистика по товарам и категориям
$totalProducts = Product::find()->count();
$activeProducts = Product::find()->where(['status' => 1])->count();
$inactiveProducts = $totalProducts - $activeProducts;
$totalCategories = Category::find()->count();

// Статистика по заказам
$totalOrders = Order::find()->count();
$pendingOrders = Order::find()->where(['status' => 'pending'])->count();
$completedOrders = Order::find()->where(['status' => 'done'])->count();
$cancelledOrders = Order::find()->where(['status' => 'cancelled'])->count();

// Сумма продаж
// Проверяем все возможные названия столбца с суммой заказа
$totalSales = 0;
try {
    // Пытаемся получить сумму разными способами
    $tableSchema = Yii::$app->db->schema->getTableSchema('order');
    if ($tableSchema && $tableSchema->getColumn('total')) {
        $totalSales = Order::find()->where(['status' => 'done'])->sum('total') ?: 0;
    } else {
        // Если столбца 'total' нет, пробуем получить сумму из связанных элементов заказа
        $completedOrders = Order::find()->where(['status' => 'done'])->all();
        foreach ($completedOrders as $order) {
            foreach ($order->items as $item) {
                $totalSales += ($item->price * $item->quantity);
            }
        }
    }
} catch (\Exception $e) {
    Yii::warning('Ошибка при расчете суммы продаж: ' . $e->getMessage());
}

// Статистика по пользователям
$totalUsers = User::find()->count();
$activeUsers = User::find()->where(['status' => User::STATUS_ACTIVE])->count();
$inactiveUsers = User::find()->where(['status' => User::STATUS_INACTIVE])->count();
$bannedUsers = User::find()->where(['status' => User::STATUS_BANNED])->count();

// Последние заказы
$latestOrders = Order::find()
    ->orderBy(['created_at' => SORT_DESC])
    ->limit(5)
    ->all();

// Последние пользователи
$latestUsers = User::find()
    ->orderBy(['created_at' => SORT_DESC])
    ->limit(5)
    ->all();

// Функция для форматирования цены
function formatPrice($price) {
    return number_format($price, 0, '.', ' ') . ' ₽';
}

// Функция для получения CSS-класса статуса заказа
function getOrderStatusClass($status) {
    switch ($status) {
        case 'pending':
            return 'warning';
        case 'done':
            return 'success';
        case 'cancelled':
            return 'danger';
        default:
            return 'secondary';
    }
}

// Функция для получения текста статуса заказа
function getOrderStatusText($status) {
    switch ($status) {
        case 'pending':
            return 'Ожидает';
        case 'done':
            return 'Выполнен';
        case 'cancelled':
            return 'Отменён';
        default:
            return $status;
    }
}

// Функция для получения CSS-класса статуса пользователя
function getUserStatusClass($status) {
    switch ($status) {
        case User::STATUS_ACTIVE:
            return 'success';
        case User::STATUS_INACTIVE:
            return 'warning';
        case User::STATUS_BANNED:
            return 'danger';
        default:
            return 'secondary';
    }
}

// Функция для получения текста статуса пользователя
function getUserStatusText($status) {
    switch ($status) {
        case User::STATUS_ACTIVE:
            return 'Активен';
        case User::STATUS_INACTIVE:
            return 'Не активирован';
        case User::STATUS_BANNED:
            return 'Заблокирован';
        default:
            return 'Неизвестно';
    }
}
?>

<div class="container-fluid">
    <!-- Информационные карточки -->
    <div class="row">
        <!-- Товары -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><?= $totalProducts ?></h3>
                    <p>Товаров</p>
                </div>
                <div class="icon">
                    <i class="fas fa-box"></i>
                </div>
                <?= Html::a('Подробнее <i class="fas fa-arrow-circle-right"></i>', ['/admin/product'], ['class' => 'small-box-footer']) ?>
            </div>
        </div>
        
        <!-- Заказы -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= $totalOrders ?></h3>
                    <p>Заказов</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <?= Html::a('Подробнее <i class="fas fa-arrow-circle-right"></i>', ['/admin/order'], ['class' => 'small-box-footer']) ?>
            </div>
        </div>
        
        <!-- Пользователи -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3><?= $totalUsers ?></h3>
                    <p>Пользователей</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <?= Html::a('Подробнее <i class="fas fa-arrow-circle-right"></i>', ['/admin/user'], ['class' => 'small-box-footer']) ?>
            </div>
        </div>
        
        <!-- Продажи -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3><?= formatPrice($totalSales) ?></h3>
                    <p>Продажи</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <?= Html::a('Подробнее <i class="fas fa-arrow-circle-right"></i>', ['/admin/order'], ['class' => 'small-box-footer']) ?>
            </div>
        </div>
    </div>
    
    <!-- Детальная статистика -->
    <div class="row">
        <!-- Статистика по товарам -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-box mr-1"></i>
                        Статистика по товарам
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Активные товары</span>
                                    <span class="info-box-number"><?= $activeProducts ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-ban"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Неактивные товары</span>
                                    <span class="info-box-number"><?= $inactiveProducts ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="progress-group">
                        <span class="progress-text">Активные товары</span>
                        <span class="float-right"><b><?= $activeProducts ?></b>/<?= $totalProducts ?></span>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: <?= $totalProducts > 0 ? ($activeProducts / $totalProducts * 100) : 0 ?>%"></div>
                        </div>
                    </div>
                    <div class="progress-group mt-3">
                        <span class="progress-text">Всего категорий</span>
                        <span class="float-right"><b><?= $totalCategories ?></b></span>
                        <div class="progress">
                            <div class="progress-bar bg-primary" style="width: 100%"></div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <?= Html::a('<i class="fas fa-plus"></i> Добавить товар', ['/admin/product/create'], ['class' => 'btn btn-success']) ?>
                        <?= Html::a('<i class="fas fa-list"></i> Все товары', ['/admin/product'], ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Статистика по заказам -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-shopping-cart mr-1"></i>
                        Статистика по заказам
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Ожидающие</span>
                                    <span class="info-box-number"><?= $pendingOrders ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Выполненные</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-times"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Отменённые</span>
                                    <span class="info-box-number"><?= $cancelledOrders ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Прогресс-бары статусов заказов -->
                    <?php if ($totalOrders > 0): ?>
                        <div class="progress-group">
                            <span class="progress-text">Ожидающие заказы</span>
                            <span class="float-right"><b><?= $pendingOrders ?></b>/<?= $totalOrders ?></span>
                            <div class="progress">
                                <div class="progress-bar bg-warning" style="width: <?= ($pendingOrders / $totalOrders * 100) ?>%"></div>
                            </div>
                        </div>
                        <div class="progress-group mt-2">
                            <span class="progress-text">Выполненные заказы</span>
                            <span class="float-right"><b><?= $completedOrders ?></b>/<?= $totalOrders ?></span>
                            <div class="progress">
                                <div class="progress-bar bg-success" style="width: <?= ($completedOrders / $totalOrders * 100) ?>%"></div>
                            </div>
                        </div>
                        <div class="progress-group mt-2">
                            <span class="progress-text">Отменённые заказы</span>
                            <span class="float-right"><b><?= $cancelledOrders ?></b>/<?= $totalOrders ?></span>
                            <div class="progress">
                                <div class="progress-bar bg-danger" style="width: <?= ($cancelledOrders / $totalOrders * 100) ?>%"></div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Пока нет заказов в системе.
                        </div>
                    <?php endif; ?>

                    <div class="mt-3">
                        <?= Html::a('<i class="fas fa-list"></i> Все заказы', ['/admin/order'], ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Последние заказы и пользователи -->
    <div class="row">
        <!-- Последние заказы -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-shopping-cart mr-1"></i>
                        Последние заказы
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="60">ID</th>
                                    <th>Клиент</th>
                                    <th>Статус</th>
                                    <th>Сумма</th>
                                    <th>Дата</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($latestOrders) > 0): ?>
                                    <?php foreach ($latestOrders as $order): ?>
                                        <tr>
                                            <td><?= $order->id ?></td>
                                            <td><?= Html::encode($order->name) ?></td>
                                            <td>
                                                <span class="badge badge-<?= getOrderStatusClass($order->status) ?>">
                                                    <?= getOrderStatusText($order->status) ?>
                                                </span>
                                            </td>
                                            <td><?= formatPrice($order->total) ?></td>
                                            <td><?= Yii::$app->formatter->asDatetime($order->created_at, 'php:d.m.Y H:i') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Заказов пока нет</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <?= Html::a('Посмотреть все заказы', ['/admin/order'], ['class' => 'btn btn-sm btn-info']) ?>
                </div>
            </div>
        </div>
        
        <!-- Последние пользователи -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users mr-1"></i>
                        Последние пользователи
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="60">ID</th>
                                    <th>Имя пользователя</th>
                                    <th>Email</th>
                                    <th>Статус</th>
                                    <th>Дата регистрации</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($latestUsers) > 0): ?>
                                    <?php foreach ($latestUsers as $user): ?>
                                        <tr>
                                            <td><?= $user->id ?></td>
                                            <td><?= Html::encode($user->username) ?></td>
                                            <td><?= Html::encode($user->email) ?></td>
                                            <td>
                                                <span class="badge badge-<?= getUserStatusClass($user->status) ?>">
                                                    <?= getUserStatusText($user->status) ?>
                                                </span>
                                            </td>
                                            <td><?= Yii::$app->formatter->asDatetime($user->created_at, 'php:d.m.Y H:i') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Пользователей пока нет</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <?= Html::a('Посмотреть всех пользователей', ['/admin/user'], ['class' => 'btn btn-sm btn-info']) ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Быстрые действия -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bolt mr-1"></i>
                        Быстрые действия
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <?= Html::a('<i class="fas fa-plus"></i> Добавить товар', ['/admin/product/create'], [
                                'class' => 'btn btn-block btn-success btn-lg',
                            ]) ?>
                        </div>
                        <div class="col-md-3">
                            <?= Html::a('<i class="fas fa-tags"></i> Добавить категорию', ['/admin/category/create'], [
                                'class' => 'btn btn-block btn-primary btn-lg',
                            ]) ?>
                        </div>
                        <div class="col-md-3">
                            <?= Html::a('<i class="fas fa-user-plus"></i> Добавить пользователя', ['/admin/user/create'], [
                                'class' => 'btn btn-block btn-warning btn-lg',
                            ]) ?>
                        </div>
                        <div class="col-md-3">
                            <?= Html::a('<i class="fas fa-cloud-download-alt"></i> Импорт из Tilda', ['/admin/product-import/import-all-from-api'], [
                                'class' => 'btn btn-block btn-info btn-lg',
                                'data' => [
                                    'confirm' => 'Вы действительно хотите импортировать товары из Tilda?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Информация о сайте -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-1"></i>
                        Информация о сайте
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-server"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">PHP версия</span>
                                    <span class="info-box-number"><?= PHP_VERSION ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-database"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">База данных</span>
                                    <span class="info-box-number"><?= Yii::$app->db->driverName ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-code"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Yii версия</span>
                                    <span class="info-box-number"><?= Yii::getVersion() ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Текущие дата и время</span>
                                    <span class="info-box-number"><?= Yii::$app->formatter->asDatetime(time(), 'php:d.m.Y H:i:s') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning mt-3">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Важно!</h5>
                        Не забудьте регулярно делать резервное копирование базы данных и файлов сайта.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>