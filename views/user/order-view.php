<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var app\models\Order $order */

$this->title = 'Заказ №' . $order->id;

$statusLabels = [
    'pending' => ['class' => 'warning', 'text' => 'Ожидание'],
    'processing' => ['class' => 'info', 'text' => 'Обработка'],
    'shipped' => ['class' => 'primary', 'text' => 'Отправлен'],
    'done' => ['class' => 'success', 'text' => 'Выполнен'],
    'cancelled' => ['class' => 'danger', 'text' => 'Отменен']
];
$statusInfo = $statusLabels[$order->status] ?? ['class' => 'secondary', 'text' => $order->status];
?>

<div class="container my-5">
    <div class="row">
        <!-- Боковое меню профиля -->
        <div class="col-md-3 mb-4">
            <div class="card bg-white shadow-sm rounded">
                <div class="list-group list-group-flush">
                    <?= Html::a('Личный кабинет', ['profile'], ['class' => 'list-group-item list-group-item-action']) ?>
                    <?= Html::a('Мои заказы', ['order-history'], ['class' => 'list-group-item list-group-item-action active']) ?>
                    <?= Html::a('Изменить пароль', ['change-password'], ['class' => 'list-group-item list-group-item-action']) ?>
                </div>
            </div>
        </div>

        <!-- Контент заказа -->
        <div class="col-md-9">
            <div class="bg-white shadow-sm rounded p-4">
                <!-- Заголовок и статус -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h5 mb-0">Заказ №<?= $order->id ?></h2>
                    <span class="badge bg-<?= $statusInfo['class'] ?> fs-6"><?= $statusInfo['text'] ?></span>
                </div>

                <!-- Информация о заказе и покупателе -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6 class="fw-bold">Информация о заказе</h6>
                        <p><strong>Дата создания:</strong> <?= Yii::$app->formatter->asDatetime($order->created_at) ?></p>
                        <p><strong>Способ доставки:</strong> <?= $order->delivery_method ? Html::encode($order->delivery_method) : 'Не указан' ?></p>
                        <p><strong>Способ оплаты:</strong> <?= $order->payment_method ? Html::encode($order->payment_method) : 'Не указан' ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6 class="fw-bold">Контактная информация</h6>
                        <p><strong>Имя:</strong> <?= Html::encode($order->name) ?></p>
                        <p><strong>Email:</strong> <?= Html::encode($order->email) ?></p>
                        <p><strong>Телефон:</strong> <?= Html::encode($order->phone) ?></p>
                        <p><strong>Адрес:</strong> <?= Html::encode($order->address) ?></p>
                    </div>
                </div>

                <!-- Комментарий -->
                <?php if ($order->comment): ?>
                    <div class="mt-3">
                        <h6 class="fw-bold">Комментарий к заказу</h6>
                        <p><?= Html::encode($order->comment) ?></p>
                    </div>
                <?php endif; ?>

                <!-- Таблица товаров -->
                <div class="mt-4">
                    <h5 class="fw-bold mb-3">Товары в заказе</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light">
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
                                    <td colspan="3" class="text-end fw-bold">Итого:</td>
                                    <td class="text-end fw-bold"><?= Yii::$app->formatter->asCurrency($order->total, 'RUB') ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Кнопка отмены -->
                <?php if ($order->status === 'pending'): ?>
                    <div class="mt-4">
                        <?= Html::a('Отменить заказ', ['cancel-order', 'id' => $order->id], [
                            'class' => 'btn btn-danger',
                            'data' => ['confirm' => 'Вы уверены, что хотите отменить этот заказ?', 'method' => 'post'],
                        ]) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
