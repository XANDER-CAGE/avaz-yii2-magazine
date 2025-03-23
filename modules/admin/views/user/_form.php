<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $model \app\models\User */

$form = ActiveForm::begin(); ?>

<?= $form->field($model, 'username')->textInput() ?>
<?= $form->field($model, 'password_hash')->passwordInput()->label($model->isNewRecord ? 'Пароль' : 'Новый пароль') ?>
<?= $form->field($model, 'is_admin')->checkbox()->label('Админ?') ?>

<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
