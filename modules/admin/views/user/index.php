<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Управление пользователями';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
            <div class="card-tools">
                <?= Html::a('<i class="fas fa-plus"></i> Создать пользователя', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
            </div>
        </div>
        <div class="card-body">
            <?php Pjax::begin(['id' => 'user-pjax']); ?>
            
            <div class="mb-3">
                <div class="btn-group">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-cog"></i> Массовые действия
                    </button>
                    <div class="dropdown-menu">
                        <?= Html::a('Активировать выбранные', ['batch-action'], [
                            'class' => 'dropdown-item',
                            'data' => [
                                'method' => 'post',
                                'params' => [
                                    'action' => 'activate',
                                ],
                                'confirm' => 'Вы уверены, что хотите активировать выбранных пользователей?',
                            ],
                            'id' => 'batch-activate',
                        ]) ?>
                        <?= Html::a('Заблокировать выбранные', ['batch-action'], [
                            'class' => 'dropdown-item',
                            'data' => [
                                'method' => 'post',
                                'params' => [
                                    'action' => 'ban',
                                ],
                                'confirm' => 'Вы уверены, что хотите заблокировать выбранных пользователей?',
                            ],
                            'id' => 'batch-ban',
                        ]) ?>
                        <?= Html::a('Удалить выбранные', ['batch-action'], [
                            'class' => 'dropdown-item text-danger',
                            'data' => [
                                'method' => 'post',
                                'params' => [
                                    'action' => 'delete',
                                ],
                                'confirm' => 'Вы уверены, что хотите удалить выбранных пользователей?',
                            ],
                            'id' => 'batch-delete',
                        ]) ?>
                    </div>
                </div>
            </div>
            
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        'checkboxOptions' => function ($model, $key, $index, $column) {
                            return ['value' => $model->id];
                        }
                    ],
                    [
                        'attribute' => 'id',
                        'headerOptions' => ['style' => 'width:60px'],
                    ],
                    [
                        'attribute' => 'username',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::a(Html::encode($model->username), ['view', 'id' => $model->id]);
                        },
                    ],
                    'email:email',
                    [
                        'attribute' => 'status',
                        'filter' => User::getStatusList(),
                        'value' => function ($model) {
                            return $model->getStatusName();
                        },
                        'contentOptions' => function ($model) {
                            $class = '';
                            switch ($model->status) {
                                case User::STATUS_ACTIVE:
                                    $class = 'text-success';
                                    break;
                                case User::STATUS_INACTIVE:
                                    $class = 'text-muted';
                                    break;
                                case User::STATUS_BANNED:
                                    $class = 'text-danger';
                                    break;
                            }
                            return ['class' => $class];
                        },
                    ],
                    [
                        'attribute' => 'role',
                        'filter' => User::getRoleList(),
                        'value' => function ($model) {
                            return $model->getRoleName();
                        },
                    ],
                    [
                        'attribute' => 'first_name',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return $model->getFullName();
                        },
                        'header' => 'Имя и фамилия',
                        'headerOptions' => ['style' => 'width:150px'],
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => 'datetime',
                        'filter' => Html::textInput('UserSearch[created_at]', $searchModel->created_at, [
                            'class' => 'form-control',
                            'placeholder' => 'Поиск по дате...'
                        ]),
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update} {delete}',
                        'buttons' => [
                            'view' => function ($url, $model, $key) {
                                return Html::a('<i class="fas fa-eye"></i>', $url, [
                                    'title' => 'Просмотр',
                                    'class' => 'btn btn-sm btn-info',
                                ]);
                            },
                            'update' => function ($url, $model, $key) {
                                return Html::a('<i class="fas fa-pencil-alt"></i>', $url, [
                                    'title' => 'Редактировать',
                                    'class' => 'btn btn-sm btn-primary',
                                ]);
                            },
                            'delete' => function ($url, $model, $key) {
                                // Не показываем кнопку удаления для текущего пользователя
                                if ($model->id == Yii::$app->user->id) {
                                    return '';
                                }
                                return Html::a('<i class="fas fa-trash"></i>', $url, [
                                    'title' => 'Удалить',
                                    'class' => 'btn btn-sm btn-danger',
                                    'data' => [
                                        'confirm' => 'Вы уверены, что хотите удалить этого пользователя?',
                                        'method' => 'post',
                                    ],
                                ]);
                            },
                        ],
                        'contentOptions' => ['style' => 'width:120px; white-space:nowrap;'],
                    ],
                ],
                'pager' => [
                    'class' => 'yii\bootstrap4\LinkPager',
                ],
                'options' => ['class' => 'table-responsive grid-view'],
                'tableOptions' => ['class' => 'table table-striped table-bordered'],
                'summary' => 'Показано {begin}-{end} из {totalCount} записей',
                'summaryOptions' => ['class' => 'text-right mb-3'],
            ]); ?>
            
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

<?php
$js = <<<JS
// Обработка массовых действий
$('#batch-activate, #batch-ban, #batch-delete').on('click', function(e) {
    var keys = $('#user-pjax').yiiGridView('getSelectedRows');
    if (keys.length === 0) {
        e.preventDefault();
        alert('Выберите хотя бы одного пользователя!');
        return false;
    }
    
    // Добавляем выбранные ID к запросу
    var paramName = $(this).data('params') ? 'selection' : 'selection';
    $(this).data('params', $.extend($(this).data('params') || {}, {selection: keys}));
});
JS;
$this->registerJs($js);
?>