<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Запрос на сброс пароля';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-request-password-reset">
    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title"><?= Html::encode($this->title) ?></h1>
                </div>
                <div class="card-body">
                    <p>Пожалуйста, укажите ваш email. Вам будет отправлена ссылка для сброса пароля.</p>

                    <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                    <?= $form->field($model, 'email')->input('email', ['autofocus' => true]) ?>

                    <div class="form-group mt-3">
                        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                    <div class="mt-3 text-center">
                        <p><?= Html::a('Вернуться на страницу входа', ['site/login']) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>