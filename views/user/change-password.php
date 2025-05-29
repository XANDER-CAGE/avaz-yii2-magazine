<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $this yii\web\View */
/** @var $model app\models\ChangePasswordForm */

$this->title = 'Изменить пароль';
$this->params['breadcrumbs'][] = ['label' => 'Личный кабинет', 'url' => ['profile']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container my-5">
    <div class="row">
        <!-- Навигация -->
        <div class="col-md-3 mb-4">
            <div class="card bg-white shadow-sm rounded">
                <div class="list-group list-group-flush">
                    <?= Html::a('Личный кабинет', ['profile'], ['class' => 'list-group-item list-group-item-action']) ?>
                    <?= Html::a('Мои заказы', ['order-history'], ['class' => 'list-group-item list-group-item-action']) ?>
                    <?= Html::a('Изменить пароль', ['change-password'], ['class' => 'list-group-item list-group-item-action active']) ?>
                    <?= Html::a('Выйти', ['/site/logout'], [
                        'class' => 'list-group-item list-group-item-action text-danger',
                        'data-method' => 'post'
                    ]) ?>
                </div>
            </div>
        </div>

        <!-- Форма смены пароля -->
        <div class="col-md-9">
            <div class="bg-white shadow-sm rounded p-4">
                <h4 class="fw-bold mb-4">Изменение пароля</h4>

                <?php $form = ActiveForm::begin(); ?>

                    <div class="mb-3">
                        <?= $form->field($model, 'current_password')
                            ->passwordInput(['class' => 'form-control', 'placeholder' => 'Текущий пароль'])
                            ->label('Текущий пароль') ?>
                    </div>

                    <div class="mb-3">
                        <?= $form->field($model, 'new_password')
                            ->passwordInput(['class' => 'form-control', 'placeholder' => 'Новый пароль'])
                            ->label('Новый пароль') ?>
                    </div>

                    <div class="mb-3">
                        <?= $form->field($model, 'new_password_confirm')
                            ->passwordInput(['class' => 'form-control', 'placeholder' => 'Повторите новый пароль'])
                            ->label('Подтверждение пароля') ?>
                    </div>

                    <div class="mt-4">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
