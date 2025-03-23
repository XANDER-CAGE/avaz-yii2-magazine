<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Product */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
                    <div class="card-tools">
                        <?= Html::a('<i class="fas fa-pencil-alt"></i> Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
                        <?= Html::a('<i class="fas fa-trash"></i> Удалить', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger btn-sm',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить этот товар?',
                                'method' => 'post',
                            ],
                        ]) ?>
                        <?= Html::a('<i class="fas fa-arrow-left"></i> Назад к списку', ['index'], ['class' => 'btn btn-default btn-sm']) ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    'id',
                                    [
                                        'attribute' => 'category_id',
                                        'format' => 'raw',
                                        'value' => $model->category ? Html::a($model->category->name, ['category/view', 'id' => $model->category_id]) : 'Не указана',
                                    ],
                                    'name',
                                    'slug',
                                    'sku',
                                    [
                                        'attribute' => 'price',
                                        'value' => $model->getFormattedPrice(),
                                    ],
                                    [
                                        'attribute' => 'status',
                                        'format' => 'raw',
                                        'value' => $model->status ? 
                                            '<span class="badge badge-success">Активен</span>' : 
                                            '<span class="badge badge-danger">Отключен</span>',
                                    ],
                                    'created_at:datetime',
                                    'updated_at:datetime',
                                ],
                                'options' => ['class' => 'table table-striped table-bordered detail-view'],
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?php if ($model->image): ?>
                                <div class="text-center mb-3">
                                    <?= Html::img($model->image, [
                                        'alt' => $model->name,
                                        'class' => 'img-fluid',
                                        'style' => 'max-height: 300px;',
                                    ]) ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Изображение не загружено.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card card-outline card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Краткое описание</h3>
                                </div>
                                <div class="card-body">
                                    <?php if ($model->short_description): ?>
                                        <?= Html::encode($model->short_description) ?>
                                    <?php else: ?>
                                        <em>Краткое описание отсутствует</em>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-outline card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Полное описание</h3>
                                </div>
                                <div class="card-body">
                                    <?php if ($model->full_description): ?>
                                        <div class="full-description">
                                            <?= nl2br(Html::encode($model->full_description)) ?>
                                        </div>
                                    <?php else: ?>
                                        <em>Полное описание отсутствует</em>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <small class="text-muted">
                        Дата создания: <?= Yii::$app->formatter->asDatetime($model->created_at) ?> | 
                        Последнее обновление: <?= Yii::$app->formatter->asDatetime($model->updated_at) ?>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>