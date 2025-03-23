<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $model \app\models\SignupForm */

$this->title = 'Регистрация';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
<?= $form->field($model, 'password')->passwordInput() ?>
<?= $form->field($model, 'is_admin')->checkbox()->label('Зарегистрировать как администратора') ?>

<div class="form-group">
    <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
