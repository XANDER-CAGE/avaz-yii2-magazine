<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Category;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Управление товарами';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
                    <div class="card-tools">
                        <?= Html::a('<i class="fas fa-plus"></i> Добавить товар', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
                        <?= Html::a('<i class="fas fa-cloud-download-alt"></i> Импорт из Tilda', ['/admin/product-import/import-all-from-api'], [
                            'class' => 'btn btn-info btn-sm ml-2',
                            'data' => [
                                'confirm' => 'Вы действительно хотите импортировать товары из Tilda?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </div>
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
                                'attribute' => 'category_id',
                                'filter' => ArrayHelper::map(Category::find()->all(), 'id', 'name'),
                                'value' => function ($model) {
                                    return $model->category ? $model->category->name : 'Не указана';
                                }
                            ],
                            [
                                'attribute' => 'name',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return Html::a(Html::encode($model->name), ['view', 'id' => $model->id], [
                                        'title' => 'Просмотр',
                                        'data-pjax' => 0,
                                    ]);
                                },
                            ],
                            [
                                'attribute' => 'image',
                                'format' => 'raw',
                                'filter' => false,
                                'value' => function ($model) {
                                    if ($model->image) {
                                        return Html::img($model->image, [
                                            'alt' => $model->name,
                                            'style' => 'max-width: 50px; max-height: 50px',
                                        ]);
                                    }
                                    return '<span class="text-muted">Нет изображения</span>';
                                },
                            ],
                            [
                                'attribute' => 'price',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return $model->getFormattedPrice();
                                },
                                'contentOptions' => ['style' => 'white-space: nowrap;'],
                            ],
                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'filter' => [1 => 'Активен', 0 => 'Отключен'],
                                'value' => function ($model) {
                                    return $model->status ? 
                                        '<span class="badge badge-success">Активен</span>' : 
                                        '<span class="badge badge-danger">Отключен</span>';
                                },
                            ],
                            [
                                'attribute' => 'created_at',
                                'format' => 'datetime',
                                'filter' => false,
                                'contentOptions' => ['style' => 'white-space: nowrap;'],
                            ],
                            
                            [
                                'class' => 'hail812\adminlte3\yii\grid\ActionColumn',
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
                                        return Html::a('<i class="fas fa-trash"></i>', $url, [
                                            'title' => 'Удалить',
                                            'class' => 'btn btn-sm btn-danger',
                                            'data' => [
                                                'confirm' => 'Вы уверены, что хотите удалить этот товар?',
                                                'method' => 'post',
                                            ],
                                        ]);
                                    },
                                ],
                                'contentOptions' => ['style' => 'white-space: nowrap; width: 120px;'],
                            ],
                        ],
                        'summaryOptions' => ['class' => 'summary mb-2'],
                        'pager' => [
                            'class' => 'yii\bootstrap4\LinkPager',
                        ],
                        'layout' => "{summary}\n{items}\n<div class='card-footer clearfix'>{pager}</div>",
                        'options' => ['class' => 'table-responsive'],
                        'tableOptions' => ['class' => 'table table-striped table-bordered'],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>