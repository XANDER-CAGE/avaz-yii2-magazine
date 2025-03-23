<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var $model app\models\ProfileForm */
/** @var $user app\models\User */

$this->title = 'Редактирование профиля';
$this->params['breadcrumbs'][] = ['label' => 'Мой профиль', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="profile-edit">
    <div class="row">
        <div class="col-md-3">
            <!-- Карточка пользователя -->
            <div class="card">
                <div class="card-body text-center">
                    <?php if ($user->avatar): ?>
                        <img src="<?= $user->avatar ?>" class="img-fluid rounded-circle mb-3" style="max-width: 150px;" alt="<?= Html::encode($user->username) ?>">
                    <?php else: ?>
                        <img src="/img/default-avatar.png" class="img-fluid rounded-circle mb-3" style="max-width: 150px;" alt="<?= Html::encode($user->username) ?>">
                    <?php endif; ?>
                    
                    <h3><?= Html::encode($user->getFullName()) ?></h3>
                    <p class="text-muted"><?= Html::encode($user->username) ?></p>
                </div>
            </div>

            <!-- Навигация профиля -->
            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="card-title">Меню профиля</h4>
                </div>
                <div class="list-group list-group-flush">
                    <?= Html::a('Мой профиль', ['profile/index'], ['class' => 'list-group-item list-group-item-action']) ?>
                    <?= Html::a('Изменить пароль', ['profile/change-password'], ['class' => 'list-group-item list-group-item-action']) ?>
                    <?= Html::a('Мои заказы', ['/order/my-orders'], ['class' => 'list-group-item list-group-item-action']) ?>
                    <?= Html::a('Удалить аккаунт', ['profile/delete-account'], [
                        'class' => 'list-group-item list-group-item-action text-danger',
                        'data' => [
                            'confirm' => 'Вы действительно хотите удалить свой аккаунт? Это действие нельзя будет отменить.',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin([
                        'id' => 'profile-form',
                        'options' => ['enctype' => 'multipart/form-data'],
                    ]); ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'email')->input('email') ?>
                            
                            <?php if (Yii::$app->params['enableEmailVerification'] ?? false): ?>
                                <div class="form-text text-muted">
                                    После изменения email вам будет отправлено письмо для подтверждения.
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    
                    <?= $form->field($model, 'avatarFile')->fileInput(['accept' => 'image/*']) ?>
                    
                    <?php if ($user->avatar): ?>
                        <div class="form-text text-muted mb-3">
                            Оставьте поле пустым, если не хотите менять текущий аватар.
                        </div>
                    <?php endif; ?>
                    
                    <div class="form-group mt-4">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-secondary']) ?>
                    </div>
                    
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>