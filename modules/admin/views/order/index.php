<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Order;

/** @var $searchModel app\models\search\OrderSearch */
/** @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="card-body">
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

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            
                            'id',
                            [
                                'attribute' => 'name',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return Html::a(Html::encode($model->name), ['view', 'id' => $model->id], [
                                        'title' => 'Просмотр заказа',
                                        'data-pjax' => 0,
                                    ]);
                                },
                            ],
                            'email:email',
                            'phone',
                            [
                                'attribute' => 'total',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return number_format($model->total, 0, '.', ' ') . ' ₽';
                                },
                                'contentOptions' => ['class' => 'text-right'],
                            ],
                            [
                                'attribute' => 'status',
                                'filter' => [
                                    'pending' => 'Ожидает',
                                    'processing' => 'В обработке',
                                    'shipped' => 'Отправлен',
                                    'done' => 'Выполнен',
                                    'cancelled' => 'Отменён'
                                ],
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
                                'attribute' => 'created_at',
                                'format' => 'datetime',
                                'filter' => false,
                            ],
                            [
                                'header' => 'Управление статусом',
                                'format' => 'raw',
                                'value' => function($model) {
                                    return Html::dropDownList(
                                        "status-{$model->id}",
                                        $model->status,
                                        [
                                            'pending' => 'Ожидает',
                                            'processing' => 'В обработке',
                                            'shipped' => 'Отправлен',
                                            'done' => 'Выполнен',
                                            'cancelled' => 'Отменён',
                                        ],
                                        [
                                            'class' => 'form-control form-control-sm status-dropdown',
                                            'data-id' => $model->id,
                                        ]
                                    );
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{view}',
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {
                                        return Html::a('<i class="fas fa-eye"></i>', $url, [
                                            'title' => 'Просмотр',
                                            'class' => 'btn btn-sm btn-info',
                                        ]);
                                    },
                                ],
                                'contentOptions' => ['style' => 'width:60px;'],
                            ],
                        ],
                        'tableOptions' => ['class' => 'table table-striped table-bordered'],
                        'pager' => [
                            'class' => 'yii\bootstrap4\LinkPager',
                        ],
                        'layout' => "{summary}\n{items}\n<div class='card-footer clearfix'>{pager}</div>",
                        'options' => ['class' => 'table-responsive'],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$js = <<<JS
// Обработка изменения статуса заказа
$('.status-dropdown').on('change', function() {
    var orderId = $(this).data('id');
    var status = $(this).val();

    $.ajax({
        url: '/admin/order/change-status-ajax',
        type: 'POST',
        data: {
            id: orderId,
            status: status,
            _csrf: yii.getCsrfToken()
        },
        success: function(data) {
            if (data.success) {
                // Показываем всплывающее уведомление
                var alertHtml = '<div class="alert alert-success alert-dismissible fade show">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
                    '<h5><i class="icon fas fa-check"></i> Успешно!</h5>' +
                    'Статус заказа №' + orderId + ' успешно изменен.' +
                    '</div>';
                
                // Добавляем уведомление вверху страницы и через 3 секунды скрываем
                $('.card-body').prepend(alertHtml);
                setTimeout(function() {
                    $('.alert').alert('close');
                }, 3000);
                
                // Обновляем статус в таблице
                location.reload();
            } else {
                alert('Ошибка при обновлении статуса');
            }
        },
        error: function() {
            alert('Ошибка запроса на сервер');
        }
    });
});
JS;

$this->registerJs($js);
?>