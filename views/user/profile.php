<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var $user app\models\User */
/** @var $orders app\models\Order[] */

$this->title = 'Личный кабинет';
?>

<div class="container my-5">
    <div class="row">
        <!-- Левая колонка -->
        <div class="col-md-4 mb-4">
            <!-- Карточка пользователя -->
            <div class="card bg-white shadow-sm rounded mb-4">
                <div class="card-body text-center">
                    <?php if ($user->avatar): ?>
                        <img src="<?= $user->avatar ?>" class="img-fluid rounded-circle mb-3" style="max-width: 150px; height: 150px; object-fit: cover;" alt="<?= Html::encode($user->username) ?>">
                    <?php else: ?>
                        <img src="/img/default-avatar.png" class="img-fluid rounded-circle mb-3" style="max-width: 150px;" alt="<?= Html::encode($user->username) ?>">
                    <?php endif; ?>
                    
                    <h3><?= Html::encode($user->getFullName()) ?></h3>
                    <p class="text-muted mb-3"><?= Html::encode($user->username) ?></p>
                    
                    <?= Html::a('Редактировать профиль', ['edit-profile'], ['class' => 'btn btn-primary btn-sm']) ?>
                </div>
            </div>

            <!-- Навигация -->
            <div class="card bg-white shadow-sm rounded">
                <div class="list-group list-group-flush">
                    <?= Html::a('Личный кабинет', ['profile'], ['class' => 'list-group-item list-group-item-action active']) ?>
                    <?= Html::a('Мои заказы', ['order-history'], ['class' => 'list-group-item list-group-item-action']) ?>
                    <?= Html::a('Изменить пароль', ['change-password'], ['class' => 'list-group-item list-group-item-action']) ?>
                    <?= Html::a('Выйти', ['/site/logout'], [
                        'class' => 'list-group-item list-group-item-action text-danger',
                        'data-method' => 'post'
                    ]) ?>
                </div>
            </div>
        </div>

        <!-- Правая колонка -->
        <div class="col-md-8">
            <div class="bg-white shadow-sm rounded p-4">
                <!-- Личная информация -->
                <div class="mb-4">
                    <h4 class="mb-3 fw-bold">Личная информация</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Имя:</strong> <?= Html::encode($user->first_name ?: 'Не указано') ?></p>
                            <p><strong>Фамилия:</strong> <?= Html::encode($user->last_name ?: 'Не указано') ?></p>
                            <p><strong>Email:</strong> <?= Html::encode($user->email) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Телефон:</strong> <?= Html::encode($user->phone ?: 'Не указан') ?></p>
                            <p><strong>Дата регистрации:</strong> <?= Yii::$app->formatter->asDate($user->created_at) ?></p>
                            <p><strong>Статус:</strong> <?= $user->getStatusName() ?></p>
                        </div>
                    </div>
                </div>

                <!-- Последние заказы -->
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-bold mb-0">Последние заказы</h4>
                        <?= Html::a('Все заказы', ['order-history'], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                    </div>

                    <?php if (!empty($orders)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Номер</th>
                                        <th>Дата</th>
                                        <th>Сумма</th>
                                        <th>Статус</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td><?= $order->id ?></td>
                                            <td><?= Yii::$app->formatter->asDate($order->created_at) ?></td>
                                            <td><?= Yii::$app->formatter->asCurrency($order->total, 'RUB') ?></td>
                                            <td>
                                                <?php 
                                                $statusLabels = [
                                                    'pending' => ['class' => 'warning', 'text' => 'Ожидание'],
                                                    'processing' => ['class' => 'info', 'text' => 'Обработка'],
                                                    'shipped' => ['class' => 'primary', 'text' => 'Отправлен'],
                                                    'done' => ['class' => 'success', 'text' => 'Выполнен'],
                                                    'cancelled' => ['class' => 'danger', 'text' => 'Отменен']
                                                ];
                                                $statusInfo = $statusLabels[$order->status] ?? ['class' => 'secondary', 'text' => $order->status];
                                                ?>
                                                <span class="badge bg-<?= $statusInfo['class'] ?>">
                                                    <?= $statusInfo['text'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?= Html::a('Детали', ['order-view', 'id' => $order->id], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-center text-muted">У вас пока нет заказов</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
