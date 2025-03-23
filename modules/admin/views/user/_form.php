<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\UserForm */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $user app\models\User */

$isNewRecord = $model->scenario === \app\modules\admin\models\UserForm::SCENARIO_CREATE;
?>

<div class="user-form">
    <?php $form = ActiveForm::begin([
        'id' => 'user-form',
        'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

    <div class="card card-primary card-outline card-tabs">
        <div class="card-header p-0 pt-1 border-bottom-0">
            <ul class="nav nav-tabs" id="user-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tab-general-tab" data-toggle="pill" href="#tab-general" role="tab" aria-selected="true">Основная информация</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-personal-tab" data-toggle="pill" href="#tab-personal" role="tab" aria-selected="false">Персональные данные</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-avatar-tab" data-toggle="pill" href="#tab-avatar" role="tab" aria-selected="false">Аватар</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="tab-general" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'type' => 'email']) ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'password')->passwordInput([
                                'maxlength' => true,
                                'autocomplete' => $isNewRecord ? 'new-password' : 'off',
                                'placeholder' => $isNewRecord ? 'Введите пароль' : 'Оставьте пустым, если не хотите менять',
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'password_confirm')->passwordInput([
                                'maxlength' => true,
                                'autocomplete' => $isNewRecord ? 'new-password' : 'off',
                                'placeholder' => $isNewRecord ? 'Повторите пароль' : 'Оставьте пустым, если не хотите менять',
                            ]) ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'role')->dropDownList(User::getRoleList(), [
                                'prompt' => 'Выберите роль',
                                'class' => 'form-control',
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'status')->dropDownList(User::getStatusList(), [
                                'prompt' => 'Выберите статус',
                                'class' => 'form-control',
                            ]) ?>
                        </div>
                    </div>
                </div>
                
                <div class="tab-pane fade" id="tab-personal" role="tabpanel">
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
                            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                </div>
                
                <div class="tab-pane fade" id="tab-avatar" role="tabpanel">
                    <?php if (isset($user) && $user->avatar): ?>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="text-center">
                                    <h5>Текущий аватар</h5>
                                    <?= Html::img($user->avatar, [
                                        'class' => 'img-thumbnail',
                                        'alt' => $user->username,
                                        'style' => 'max-width: 200px; max-height: 200px;'
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?= $form->field($model, 'avatarFile')->fileInput(['class' => 'form-control']) ?>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Поддерживаемые форматы: PNG, JPG, GIF. Максимальный размер файла: 2 МБ.
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="form-group">
                <?= Html::submitButton($isNewRecord ? '<i class="fas fa-plus"></i> Создать' : '<i class="fas fa-save"></i> Сохранить', [
                    'class' => $isNewRecord ? 'btn btn-success' : 'btn btn-primary'
                ]) ?>
                <?= Html::a('<i class="fas fa-times"></i> Отмена', ['index'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$js = <<<JS
// Если нажимаем Enter в полях формы, то не отправляем форму, а переходим на следующую вкладку
$('#user-form input').keydown(function(event) {
    if (event.keyCode == 13) {
        event.preventDefault();
        var activeTab = $('.nav-tabs .active').attr('id');
        var nextTab = '';
        
        switch (activeTab) {
            case 'tab-general-tab':
                nextTab = 'tab-personal-tab';
                break;
            case 'tab-personal-tab':
                nextTab = 'tab-avatar-tab';
                break;
            case 'tab-avatar-tab':
                $('#user-form').submit();
                break;
        }
        
        if (nextTab) {
            $('#' + nextTab).click();
        }
        
        return false;
    }
});
JS;
$this->registerJs($js);
?>