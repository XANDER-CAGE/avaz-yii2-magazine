<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Заказ #' . $model->id;
?>

<h1><?= Html::encode($this->title) ?></h1>

<?= \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'name',
        'email:email',
        'phone',
        'address:ntext',
        'total',
        'status',
        'admin_comment:ntext',
        'created_at',
    ],
]) ?>


<h3 class="mt-4">Содержимое заказа</h3>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Товар</th>
            <th>Цена</th>
            <th>Кол-во</th>
            <th>Сумма</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($model->items as $item): ?>
            <tr>
                <td><?= Html::encode($item->name) ?></td>
                <td><?= $item->price ?> ₽</td>
                <td><?= $item->quantity ?></td>
                <td><?= $item->sum ?> ₽</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="card card-secondary mt-4">
    <div class="card-header">Комментарий администратора</div>
    <div class="card-body">
        <?php $form = \yii\widgets\ActiveForm::begin(['action' => ['comment', 'id' => $model->id]]); ?>
        <?= $form->field($model, 'admin_comment')->textarea(['rows' => 4]) ?>
        <div class="form-group">
            <?= \yii\helpers\Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php \yii\widgets\ActiveForm::end(); ?>
    </div>
</div>


<div class="mb-3">
    <?= Html::a('Пометить как выполненный', ['change-status', 'id' => $model->id, 'status' => 'done'], [
        'class' => 'btn btn-success',
        'data-confirm' => 'Подтвердить выполнение заказа?',
    ]) ?>

    <?= Html::a('Отменить заказ', ['change-status', 'id' => $model->id, 'status' => 'cancelled'], [
        'class' => 'btn btn-danger',
        'data-confirm' => 'Уверены, что хотите отменить заказ?',
    ]) ?>
</div>

