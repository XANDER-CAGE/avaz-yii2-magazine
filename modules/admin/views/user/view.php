<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $orderDataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователь: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-view">
    <div class="row">
        <div class="col-md-4">
            <!-- Профиль пользователя -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Информация о пользователе</h3>
                    <div class="card-tools">
                        <?= Html::a('<i class="fas fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm', 'title' => 'Редактировать']) ?>
                        <?php if ($model->id != Yii::$app->user->id): ?>
                            <?= Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger btn-sm',
                                'title' => 'Удалить',
                                'data' => [
                                    'confirm' => 'Вы уверены, что хотите удалить этого пользователя?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="card-body box-profile">
                    <div class="text-center mb-3">
                        <?php if ($model->avatar): ?>
                            <img class="profile-user-img img-fluid img-circle" src="<?= $model->avatar ?>" alt="User profile picture">
                        <?php else: ?>
                            <img class="profile-user-img img-fluid img-circle" src="/img/default-avatar.png" alt="User profile picture">
                        <?php endif; ?>
                    </div>

                    <h3 class="profile-username text-center"><?= Html::encode($model->getFullName()) ?></h3>
                    <p class="text-muted text-center"><?= Html::encode($model->username) ?></p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Роль</b> <span class="float-right"><?= Html::encode($model->getRoleName()) ?></span>
                        </li>
                        <li class="list-group-item">
                            <b>Статус</b> <span class="float-right">
                                <?php
                                $statusClass = '';
                                switch ($model->status) {
                                    case \app\models\User::STATUS_ACTIVE:
                                        $statusClass = 'badge badge-success';
                                        break;
                                    case \app\models\User::STATUS_INACTIVE:
                                        $statusClass = 'badge badge-secondary';
                                        break;
                                    case \app\models\User::STATUS_BANNED:
                                        $statusClass = 'badge badge-danger';
                                        break;
                                }
                                echo Html::tag('span', $model->getStatusName(), ['class' => $statusClass]);
                                ?>
                            </span>
                        </li>
                        <li class="list-group-item">
                            <b>Дата регистрации</b> <span class="float-right"><?= Yii::$app->formatter->asDatetime($model->created_at) ?></span>
                        </li>
                    </ul>

                    <?php if ($model->id != Yii::$app->user->id): ?>
                        <div class="btn-group btn-block">
                            <?php if ($model->status != \app\models\User::STATUS_ACTIVE): ?>
                                <?= Html::a('Активировать', ['batch-action'], [
                                    'class' => 'btn btn-success',
                                    'data' => [
                                        'method' => 'post',
                                        'params' => [
                                            'action' => 'activate',
                                            'selection' => [$model->id],
                                        ],
                                    ],
                                ]) ?>
                            <?php endif; ?>
                            
                            <?php if ($model->status != \app\models\User::STATUS_BANNED): ?>
                                <?= Html::a('Заблокировать', ['batch-action'], [
                                    'class' => 'btn btn-warning',
                                    'data' => [
                                        'method' => 'post',
                                        'params' => [
                                            'action' => 'ban',
                                            'selection' => [$model->id],
                                        ],
                                        'confirm' => 'Вы уверены, что хотите заблокировать этого пользователя?',
                                    ],
                                ]) ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Детальная информация -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Детальная информация</h3>
                </div>
                
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'username',
                            'email:email',
                            'first_name',
                            'last_name',
                            'phone',
                            [
                                'attribute' => 'avatar',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    if ($model->avatar) {
                                        return Html::img($model->avatar, ['style' => 'max-width:200px;']);
                                    }
                                    return '<span class="text-muted">Не загружен</span>';
                                },
                            ],
                            'created_at:datetime',
                            'updated_at:datetime',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <!-- История заказов -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">История заказов</h3>
                </div>
                <div class="card-body">
                    <?php Pjax::begin(); ?>
                    
                    <?= GridView::widget([
                        'dataProvider' => $orderDataProvider,
                        'columns' => [
                            'id',
                            [
                                'attribute' => 'created_at',
                                'format' => 'datetime',
                            ],
                            [
                                'attribute' => 'total',
                                'format' => 'currency',
                                'contentOptions' => ['class' => 'text-right'],
                            ],
                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    $statusClass = '';
                                    switch ($model->status) {
                                        case 'pending':
                                            $statusClass = 'badge badge-warning';
                                            $statusText = 'Ожидает';
                                            break;
                                        case 'done':
                                            $statusClass = 'badge badge-success';
                                            $statusText = 'Выполнен';
                                            break;
                                        case 'cancelled':
                                            $statusClass = 'badge badge-danger';
                                            $statusText = 'Отменен';
                                            break;
                                        default:
                                            $statusClass = 'badge badge-secondary';
                                            $statusText = $model->status;
                                    }
                                    return Html::tag('span', $statusText, ['class' => $statusClass]);
                                },
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{view}',
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {
                                        return Html::a('<i class="fas fa-eye"></i>', ['/admin/order/view', 'id' => $model->id], [
                                            'title' => 'Просмотр',
                                            'class' => 'btn btn-sm btn-info',
                                        ]);
                                    },
                                ],
                                'contentOptions' => ['style' => 'width:60px;'],
                            ],
                        ],
                        'layout' => "{summary}\n{items}\n<div class='card-footer clearfix'>{pager}</div>",
                        'pager' => [
                            'class' => 'yii\bootstrap4\LinkPager',
                        ],
                    ]); ?>
                    
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>