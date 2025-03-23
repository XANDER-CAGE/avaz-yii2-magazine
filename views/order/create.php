<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Оформление заказа';
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="order-form mt-4">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['type' => 'email']) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textarea(['rows' => 3]) ?>

    <div class="form-group">
        <?= Html::submitButton('Оформить заказ', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['cart/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
