<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\captcha\Captcha;

/** @var $model \app\models\SignupForm */

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-signup">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title"><?= Html::encode($this->title) ?></h1>
                </div>
                <div class="card-body">
                    <p>Заполните следующие поля для регистрации:</p>

                    <?php $form = ActiveForm::begin([
                        'id' => 'signup-form',
                        'enableAjaxValidation' => true,
                    ]); ?>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'email')->input('email') ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'first_name')->textInput() ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'last_name')->textInput() ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'password')->passwordInput() ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'password_confirm')->passwordInput() ?>
                        </div>
                    </div>

                    <?= $form->field($model, 'phone')->textInput() ?>

                    <?= $form->field($model, 'terms_accepted')->checkbox([
                        'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"help-block\">{error}</div>",
                    ]) ?>

                    <?php if (Yii::$app->user->can('admin')): ?>
                        <?= $form->field($model, 'is_admin')->checkbox([
                            'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"help-block\">{error}</div>",
                        ]) ?>
                    <?php endif; ?>

                    <div class="form-group mt-4">
                        <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary btn-block', 'name' => 'signup-button']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                    <div class="mt-3 text-center">
                        <p>Уже есть аккаунт? <?= Html::a('Войти', ['site/login']) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>