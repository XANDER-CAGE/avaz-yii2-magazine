<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-login">
    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title"><?= Html::encode($this->title) ?></h1>
                </div>
                <div class="card-body">
                    <p>Пожалуйста, заполните следующие поля для входа:</p>

                    <?php $form = ActiveForm::begin([
                        'id' => 'login-form',
                        'enableAjaxValidation' => true,
                    ]); ?>

                    <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => 'Логин или Email']) ?>

                    <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Пароль']) ?>

                    <?= $form->field($model, 'rememberMe')->checkbox([
                        'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"help-block\">{error}</div>",
                    ]) ?>

                    <div class="form-group mt-3">
                        <?= Html::submitButton('Войти', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                    <div class="mt-3 text-center">
                        <p><?= Html::a('Забыли пароль?', ['site/request-password-reset']) ?></p>
                        <p>Нет аккаунта? <?= Html::a('Зарегистрироваться', ['site/signup']) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>