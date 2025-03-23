<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var $user app\models\User */

$this->title = 'Мой профиль';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="profile-index">
    <div class="row">
        <div class="col-md-3">
            <!-- Карточка пользователя -->
            <div class="card">
                <div class="card-body text-center">
                    <?php if ($user->avatar): ?>
                        <img src="<?= $user->avatar ?>" class="img-fluid rounded-circle mb-3" style="max-width: 150px;" alt="<?= Html::encode($user->username) ?>">
                    <?php else: ?>
                        <img src="/img/default-avatar.png" class="img-fluid rounded-circle mb-3" style="max-width: 150px;" alt="<?= Html::encode($user->username) ?>">
                    <?php endif; ?>
                    
                    <h3><?= Html::encode($user->getFullName()) ?></h3>
                    <p class="text-muted"><?= Html::encode($user->username) ?></p>
                    
                    <div class="mt-3">
                        <?= Html::a('Редактировать профиль', ['profile/edit'], ['class' => 'btn btn-primary btn-sm']) ?>
                    </div>
                </div>
            </div>

            <!-- Навигация профиля -->
            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="card-title">Меню профиля</h4>
                </div>
                <div class="list-group list-group-flush">
                    <?= Html::a('Мой профиль', ['profile/index'], ['class' => 'list-group-item list-group-item-action active']) ?>
                    <?= Html::a('Изменить пароль', ['profile/change-password'], ['class' => 'list-group-item list-group-item-action']) ?>
                    <?= Html::a('Мои заказы', ['/order/my-orders'], ['class' => 'list-group-item list-group-item-action']) ?>
                    <?= Html::a('Удалить аккаунт', ['profile/delete-account'], [
                        'class' => 'list-group-item list-group-item-action text-danger',
                        'data' => [
                            'confirm' => 'Вы действительно хотите удалить свой аккаунт? Это действие нельзя будет отменить.',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <!-- Основная информация -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Информация профиля</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Логин:</strong> <?= Html::encode($user->username) ?></p>
                            <p><strong>Email:</strong> <?= Html::encode($user->email) ?></p>
                            <p><strong>Имя:</strong> <?= Html::encode($user->first_name) ?: '<em>Не указано</em>' ?></p>
                            <p><strong>Фамилия:</strong> <?= Html::encode($user->last_name) ?: '<em>Не указана</em>' ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Телефон:</strong> <?= Html::encode($user->phone) ?: '<em>Не указан</em>' ?></p>
                            <p><strong>Роль:</strong> <?= Html::encode($user->getRoleName()) ?></p>
                            <p><strong>Статус:</strong> <?= Html::encode($user->getStatusName()) ?></p>
                            <p><strong>Дата регистрации:</strong> <?= Yii::$app->formatter->asDatetime($user->created_at) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Последние заказы -->
            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Мои последние заказы</h4>
                    <?= Html::a('Все заказы', ['/order/my-orders'], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                </div>
                <div class="card-body">
                    <?php
                    // Здесь можно добавить список последних заказов пользователя
                    // Например:
                    $orders = \app\models\Order::find()
                        ->where(['user_id' => $user->id])
                        ->orderBy(['created_at' => SORT_DESC])
                        ->limit(5)
                        ->all();
                    
                    if (!empty($orders)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>№ заказа</th>
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
                                                switch ($order->status) {
                                                    case 'pending':
                                                        echo '<span class="badge bg-warning">Ожидает</span>';
                                                        break;
                                                    case 'done':
                                                        echo '<span class="badge bg-success">Выполнен</span>';
                                                        break;
                                                    case 'cancelled':
                                                        echo '<span class="badge bg-danger">Отменен</span>';
                                                        break;
                                                    default:
                                                        echo '<span class="badge bg-secondary">' . Html::encode($order->status) . '</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?= Html::a('Просмотр', ['/order/view', 'id' => $order->id], ['class' => 'btn btn-sm btn-info']) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center">У вас пока нет заказов</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>