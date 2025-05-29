<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var yii\web\View $this */

$this->title = 'История заказов';
?>

<div class="container my-5">
    <div class="row">
        <!-- Боковое меню -->
        <div class="col-md-3 mb-4">
            <div class="card bg-white shadow-sm rounded">
                <div class="list-group list-group-flush">
                    <?= Html::a('Личный кабинет', ['profile'], ['class' => 'list-group-item list-group-item-action']) ?>
                    <?= Html::a('Мои заказы', ['order-history'], ['class' => 'list-group-item list-group-item-action active']) ?>
                    <?= Html::a('Изменить пароль', ['change-password'], ['class' => 'list-group-item list-group-item-action']) ?>
                    <?= Html::a('Выйти', ['/site/logout'], [
                        'class' => 'list-group-item list-group-item-action text-danger',
                        'data-method' => 'post'
                    ]) ?>
                </div>
            </div>
        </div>

        <!-- Контент -->
        <div class="col-md-9">
            <div class="bg-white shadow-sm rounded p-4">
                <h4 class="fw-bold mb-4">История заказов</h4>

                <?php if ($dataProvider->getCount() > 0): ?>
                    <?php Pjax::begin(); ?>
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
                                <?php foreach ($dataProvider->getModels() as $order): ?>
                                    <?php
                                    $statusLabels = [
                                        'pending' => ['class' => 'warning', 'text' => 'Ожидание'],
                                        'processing' => ['class' => 'info', 'text' => 'Обработка'],
                                        'shipped' => ['class' => 'primary', 'text' => 'Отправлен'],
                                        'done' => ['class' => 'success', 'text' => 'Выполнен'],
                                        'cancelled' => ['class' => 'danger', 'text' => 'Отменен'],
                                    ];
                                    $statusInfo = $statusLabels[$order->status] ?? ['class' => 'secondary', 'text' => $order->status];
                                    ?>
                                    <tr>
                                        <td><?= $order->id ?></td>
                                        <td><?= Yii::$app->formatter->asDate($order->created_at) ?></td>
                                        <td><?= Yii::$app->formatter->asCurrency($order->total, 'RUB') ?></td>
                                        <td><span class="badge bg-<?= $statusInfo['class'] ?>"><?= $statusInfo['text'] ?></span></td>
                                        <td><?= Html::a('Подробнее', ['order-view', 'id' => $order->id], ['class' => 'btn btn-sm btn-outline-primary']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <?= LinkPager::widget([
                            'pagination' => $dataProvider->pagination,
                            'options' => ['class' => 'pagination justify-content-center'],
                            'maxButtonCount' => 5,
                            'prevPageLabel' => '‹',
                            'nextPageLabel' => '›',
                        ]) ?>
                    </div>
                    <?php Pjax::end(); ?>
                <?php else: ?>
                    <p class="text-muted text-center mb-0">У вас пока нет заказов.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
