<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Установка нового пароля';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-reset-password">
    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title"><?= Html::encode($this->title) ?></h1>
                </div>
                <div class="card-body">
                    <p>Пожалуйста, выберите новый пароль:</p>

                    <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                    <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>

                    <?= $form->field($model, 'password_confirm')->passwordInput() ?>

                    <div class="form-group mt-3">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>