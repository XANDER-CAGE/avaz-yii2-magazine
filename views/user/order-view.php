<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var app\models\Order $order */

$this->title = 'Заказ №' . $order->id;
$this->params['breadcrumbs'][] = ['label' => 'Личный кабинет', 'url' => ['profile']];
$this->params['breadcrumbs'][] = ['label' => 'История заказов', 'url' => ['order-history']];
$this->params['breadcrumbs'][] = $this->title;

$statusLabels = [
    'pending' => ['class' => 'warning', 'text' => 'Ожидание'],
    'processing' => ['class' => 'info', 'text' => 'Обработка'],
    'shipped' => ['class' => 'primary', 'text' => 'Отправлен'],
    'done' => ['class' => 'success', 'text' => 'Выполнен'],
    'cancelled' => ['class' => 'danger', 'text' => 'Отменен']
];
$statusInfo = $statusLabels[$order->status] ?? ['class' => 'secondary', 'text' => $order->status];
?>

<div class="order-view">
    <div class="row">
        <div class="col-md-3">
            <!-- Боковое меню профиля -->
            <div class="card">
                <div class="list-group list-group-flush">
                    <?= Html::a('Личный кабинет', ['profile'], ['class' => 'list-group-item list-group-item-action']) ?>
                    <?= Html::a('Мои заказы', ['order-history'], ['class' => 'list-group-item list-group-item-action active']) ?>
                    <?= Html::a('Изменить пароль', ['change-password'], ['class' => 'list-group-item list-group-item-action']) ?>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h1 class="mb-0">Заказ №<?= $order->id ?></h1>
                    <span class="badge bg-<?= $statusInfo['class'] ?> fs-6">
                        <?= $statusInfo['text'] ?>
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Информация о заказе</h5>
                            <p><strong>Дата создания:</strong> <?= Yii::$app->formatter->asDatetime($order->created_at) ?></p>
                            <p><strong>Способ доставки:</strong> <?= $order->delivery_method ? Html::encode($order->delivery_method) : 'Не указан' ?></p>
                            <p><strong>Способ оплаты:</strong> <?= $order->payment_method ? Html::encode($order->payment_method) : 'Не указан' ?></p>
                        </div>
                        <div class="col-md-6">
                            <h5>Контактная информация</h5>
                            <p><strong>Имя:</strong> <?= Html::encode($order->name) ?></p>
                            <p><strong>Email:</strong> <?= Html::encode($order->email) ?></p>
                            <p><strong>Телефон:</strong> <?= Html::encode($order->phone) ?></p>
                            <p><strong>Адрес:</strong> <?= Html::encode($order->address) ?></p>
                        </div>
                    </div>

                    <?php if ($order->comment): ?>
                        <div class="mt-3">
                            <h5>Комментарий к заказу</h5>
                            <p><?= Html::encode($order->comment) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Товары в заказе</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Товар</th>
                                <th class="text-center">Количество</th>
                                <th class="text-end">Цена</th>
                                <th class="text-end">Сумма</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order->items as $item): ?>
                                <tr>
                                    <td>
                                        <?= Html::encode($item->name) ?>
                                        <?php if ($item->product): ?>
                                            <br><small class="text-muted">Артикул: <?= Html::encode($item->product->sku) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><?= $item->quantity ?></td>
                                    <td class="text-end"><?= Yii::$app->formatter->asCurrency($item->price, 'RUB') ?></td>
                                    <td class="text-end"><?= Yii::$app->formatter->asCurrency($item->sum, 'RUB') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Итого:</strong></td>
                                <td class="text-end"><strong><?= Yii::$app->formatter->asCurrency($order->total, 'RUB') ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <?php if ($order->status === 'pending'): ?>
                <div class="card mt-3">
                    <div class="card-body">
                        <?= Html::a('Отменить заказ', ['cancel-order', 'id' => $order->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите отменить этот заказ?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>