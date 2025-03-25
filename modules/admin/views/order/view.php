<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = 'Заказ #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-fluid">
    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check"></i> Успешно!</h5>
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>
    
    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban"></i> Ошибка!</h5>
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Информация о заказе -->
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-2"></i> Информация о заказе
                    </h3>
                    <div class="card-tools">
                        <?= Html::a('<i class="fas fa-arrow-left"></i> К списку', ['index'], ['class' => 'btn btn-sm btn-default']) ?>
                    </div>
                </div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            [
                                'attribute' => 'created_at',
                                'format' => 'datetime',
                            ],
                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'value' => function($model) {
                                    $statusClasses = [
                                        'pending' => 'badge badge-warning',
                                        'processing' => 'badge badge-info',
                                        'shipped' => 'badge badge-primary',
                                        'done' => 'badge badge-success',
                                        'cancelled' => 'badge badge-danger'
                                    ];
                                    
                                    $statusLabels = [
                                        'pending' => 'Ожидает',
                                        'processing' => 'В обработке',
                                        'shipped' => 'Отправлен',
                                        'done' => 'Выполнен',
                                        'cancelled' => 'Отменён'
                                    ];
                                    
                                    $class = isset($statusClasses[$model->status]) ? $statusClasses[$model->status] : 'badge badge-secondary';
                                    $label = isset($statusLabels[$model->status]) ? $statusLabels[$model->status] : $model->status;
                                    
                                    return Html::tag('span', $label, ['class' => $class]);
                                }
                            ],
                            [
                                'attribute' => 'total',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return '<strong>' . number_format($model->total, 0, '.', ' ') . ' ₽</strong>';
                                },
                            ],
                        ],
                    ]) ?>
                    
                    <div class="mt-3">
                        <div class="btn-group">
                            <?= Html::a('<i class="fas fa-check"></i> Выполнен', ['change-status', 'id' => $model->id, 'status' => 'done'], [
                                'class' => 'btn btn-success',
                                'data' => [
                                    'confirm' => 'Вы уверены, что хотите отметить заказ как выполненный?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                            
                            <?= Html::a('<i class="fas fa-clock"></i> В обработке', ['change-status', 'id' => $model->id, 'status' => 'processing'], [
                                'class' => 'btn btn-info',
                                'data' => [
                                    'method' => 'post',
                                ],
                            ]) ?>
                            
                            <?= Html::a('<i class="fas fa-truck"></i> Отправлен', ['change-status', 'id' => $model->id, 'status' => 'shipped'], [
                                'class' => 'btn btn-primary',
                                'data' => [
                                    'method' => 'post',
                                ],
                            ]) ?>
                            
                            <?= Html::a('<i class="fas fa-times"></i> Отменить', ['change-status', 'id' => $model->id, 'status' => 'cancelled'], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => 'Вы уверены, что хотите отменить заказ?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Комментарий администратора -->
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-comments mr-2"></i> Комментарий администратора
                    </h3>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin(['action' => ['comment', 'id' => $model->id]]); ?>
                    
                    <?= $form->field($model, 'admin_comment')->textarea([
                        'rows' => 4,
                        'placeholder' => 'Введите комментарий к заказу (виден только администраторам)'
                    ])->label(false) ?>
                    
                    <div class="form-group">
                        <?= Html::submitButton('<i class="fas fa-save"></i> Сохранить комментарий', [
                            'class' => 'btn btn-primary'
                        ]) ?>
                    </div>
                    
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        
        <!-- Информация о клиенте -->
        <div class="col-md-6">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user mr-2"></i> Информация о клиенте
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tr>
                            <th style="width: 30%">Имя:</th>
                            <td><?= Html::encode($model->name) ?></td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td><?= Html::mailto(Html::encode($model->email)) ?></td>
                        </tr>
                        <tr>
                            <th>Телефон:</th>
                            <td><?= Html::encode($model->phone) ?></td>
                        </tr>
                        <tr>
                            <th>Адрес доставки:</th>
                            <td><?= nl2br(Html::encode($model->address)) ?></td>
                        </tr>
                        <?php if ($model->comment): ?>
                        <tr>
                            <th>Комментарий клиента:</th>
                            <td><?= nl2br(Html::encode($model->comment)) ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($model->user): ?>
                        <tr>
                            <th>Пользователь:</th>
                            <td>
                                <?= Html::a(
                                    Html::encode($model->user->username),
                                    ['/admin/user/view', 'id' => $model->user_id],
                                    ['class' => 'btn btn-sm btn-info']
                                ) ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
            
            <?php if ($model->payment_method || $model->delivery_method): ?>
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-truck mr-2"></i> Доставка и оплата
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <?php if ($model->delivery_method): ?>
                        <tr>
                            <th style="width: 30%">Способ доставки:</th>
                            <td><?= Html::encode($model->delivery_method) ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($model->payment_method): ?>
                        <tr>
                            <th>Способ оплаты:</th>
                            <td><?= Html::encode($model->payment_method) ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Содержимое заказа -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-shopping-cart mr-2"></i> Содержимое заказа
                    </h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 50px">№</th>
                                <th>Товар</th>
                                <th style="width: 120px" class="text-right">Цена</th>
                                <th style="width: 100px" class="text-center">Кол-во</th>
                                <th style="width: 150px" class="text-right">Сумма</th>
                                <th style="width: 100px" class="text-center">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($model->items as $index => $item): ?>
                            <tr>
                                <td class="text-center"><?= $index + 1 ?></td>
                                <td>
                                    <?= Html::encode($item->name) ?>
                                    <?php if ($item->product): ?>
                                        <?= Html::a(
                                            '<i class="fas fa-external-link-alt"></i>',
                                            ['/admin/product/view', 'id' => $item->product_id],
                                            ['class' => 'ml-2', 'title' => 'Перейти к товару', 'target' => '_blank']
                                        ) ?>
                                    <?php endif; ?>
                                </td>
                                <td class="text-right"><?= number_format($item->price, 0, '.', ' ') ?> ₽</td>
                                <td class="text-center"><?= $item->quantity ?></td>
                                <td class="text-right"><strong><?= number_format($item->sum, 0, '.', ' ') ?> ₽</strong></td>
                                <td class="text-center">
                                    <?php if ($item->product): ?>
                                    <?= Html::a(
                                        '<i class="fas fa-eye"></i>',
                                        ['/admin/product/view', 'id' => $item->product_id],
                                        ['class' => 'btn btn-xs btn-info', 'title' => 'Просмотр товара']
                                    ) ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-right"><strong>Итого:</strong></td>
                                <td class="text-right">
                                    <strong class="text-lg">
                                        <?= number_format($model->total, 0, '.', ' ') ?> ₽
                                    </strong>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- История заказа -->
    <?php if (class_exists('app\models\OrderLog')): ?>
    <?php $logs = \app\models\OrderLog::find()->where(['order_id' => $model->id])->orderBy(['created_at' => SORT_DESC])->all(); ?>
    <?php if (!empty($logs)): ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history mr-2"></i> История заказа
                    </h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 170px">Дата и время</th>
                                <th style="width: 200px">Пользователь</th>
                                <th style="width: 150px">Действие</th>
                                <th>Комментарий</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $log): ?>
                            <tr>
                                <td><?= Yii::$app->formatter->asDatetime($log->created_at) ?></td>
                                <td>
                                    <?php if ($log->user): ?>
                                        <?= Html::encode($log->user->username) ?>
                                    <?php else: ?>
                                        <span class="text-muted">Система</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= Html::encode($log->action) ?></td>
                                <td><?= nl2br(Html::encode($log->comment)) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>

<?php
$js = <<<JS
    // Подтверждение действий по изменению статуса
    $('.btn-success, .btn-danger').on('click', function(e) {
        if (!confirm($(this).data('confirm'))) {
            e.preventDefault();
            return false;
        }
    });
JS;
$this->registerJs($js);
?>