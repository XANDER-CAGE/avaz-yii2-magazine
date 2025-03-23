<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/** @var $searchModel app\models\search\OrderSearch */
/** @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        'id',
        'name',
        'email:email',
        'total',
        [
            'attribute' => 'status',
            'filter' => [
                'pending' => 'Ожидает',
                'done' => 'Выполнен',
                'cancelled' => 'Отменён'
            ],
            'value' => function($model) {
                return match ($model->status) {
                    'pending' => 'Ожидает',
                    'done' => 'Выполнен',
                    'cancelled' => 'Отменён',
                    default => $model->status
                };
            }
        ],
        'created_at',
        [
            'attribute' => 'status',
            'format' => 'raw',
            'filter' => [
                'pending' => 'Ожидает',
                'done' => 'Выполнен',
                'cancelled' => 'Отменён'
            ],
            'value' => function($model) {
                return \yii\helpers\Html::dropDownList(
                    "status-{$model->id}",
                    $model->status,
                    [
                        'pending' => 'Ожидает',
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
    ],
]);

$script = <<<JS
$('.status-dropdown').on('change', function() {
    var orderId = $(this).data('id');
    var status = $(this).val();

    $.ajax({
        url: '/index.php?r=admin/order/change-status-ajax',
        type: 'POST',
        data: {
            id: orderId,
            status: status,
            _csrf: yii.getCsrfToken()
        },
        success: function(data) {
            if (data.success) {
                $.toast({
                    heading: 'Успешно',
                    text: 'Статус обновлён',
                    position: 'top-right',
                    icon: 'success',
                    stack: false
                });
            } else {
                alert('Ошибка при обновлении статуса');
            }
        },
        error: function() {
            alert('Ошибка запроса');
        }
    });
});
JS;

$this->registerJs($script);



?>
