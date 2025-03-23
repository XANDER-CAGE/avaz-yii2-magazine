<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Category;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Product */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="product-form">
    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'id' => 'product-form',
    ]); ?>

    <div class="card card-primary card-outline card-tabs">
        <div class="card-header p-0 pt-1 border-bottom-0">
            <ul class="nav nav-tabs" id="product-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tab-general-tab" data-toggle="pill" href="#tab-general" role="tab" aria-controls="tab-general" aria-selected="true">Основная информация</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-descriptions-tab" data-toggle="pill" href="#tab-descriptions" role="tab" aria-controls="tab-descriptions" aria-selected="false">Описания</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-image-tab" data-toggle="pill" href="#tab-image" role="tab" aria-controls="tab-image" aria-selected="false">Изображение</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-seo-tab" data-toggle="pill" href="#tab-seo" role="tab" aria-controls="tab-seo" aria-selected="false">SEO</a>
                </li>
            </ul>
        </div>
        
        <div class="card-body">
            <div class="tab-content" id="product-tabs-content">
                <div class="tab-pane fade show active" id="tab-general" role="tabpanel" aria-labelledby="tab-general-tab">
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'name')->textInput([
                                'maxlength' => true,
                                'placeholder' => 'Введите название товара',
                                'class' => 'form-control'
                            ]) ?>

                            <?= $form->field($model, 'category_id')->dropDownList(
                                ArrayHelper::map(Category::find()->all(), 'id', 'name'),
                                [
                                    'prompt' => 'Выберите категорию',
                                    'class' => 'form-control select2'
                                ]
                            ) ?>
                        </div>
                        
                        <div class="col-md-6">
                            <?= $form->field($model, 'sku')->textInput([
                                'maxlength' => true,
                                'placeholder' => 'Введите артикул товара'
                            ]) ?>

                            <?= $form->field($model, 'price')->textInput([
                                'type' => 'number',
                                'step' => '0.01',
                                'min' => '0',
                                'placeholder' => 'Введите цену товара'
                            ]) ?>
                        </div>
                    </div>

                    <?= $form->field($model, 'status')->dropDownList([
                        1 => 'Активен',
                        0 => 'Отключен'
                    ], [
                        'class' => 'form-control'
                    ]) ?>
                </div>
                
                <div class="tab-pane fade" id="tab-descriptions" role="tabpanel" aria-labelledby="tab-descriptions-tab">
                    <?= $form->field($model, 'short_description')->textarea([
                        'rows' => 3,
                        'placeholder' => 'Введите краткое описание товара'
                    ]) ?>

                    <?= $form->field($model, 'full_description')->textarea([
                        'rows' => 10,
                        'placeholder' => 'Введите полное описание товара'
                    ]) ?>
                </div>
                
                <div class="tab-pane fade" id="tab-image" role="tabpanel" aria-labelledby="tab-image-tab">
                    <?php if (!$model->isNewRecord && $model->image): ?>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="text-center">
                                    <h5>Текущее изображение</h5>
                                    <?= Html::img($model->image, [
                                        'class' => 'img-thumbnail',
                                        'alt' => $model->name,
                                        'style' => 'max-width: 300px; max-height: 300px;'
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?= $form->field($model, 'imageFile')->fileInput(['class' => 'form-control']) ?>
                    
                    <?php if (!$model->isNewRecord && $model->image): ?>
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" id="remove-image" name="remove_image" class="form-check-input">
                                <label class="form-check-label" for="remove-image">Удалить текущее изображение</label>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="tab-pane fade" id="tab-seo" role="tabpanel" aria-labelledby="tab-seo-tab">
                    <?= $form->field($model, 'slug')->textInput([
                        'maxlength' => true,
                        'placeholder' => 'URL-адрес товара (если пусто, будет сгенерирован автоматически)'
                    ]) ?>
                </div>
            </div>
        </div>
        
        <div class="card-footer">
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? '<i class="fas fa-plus"></i> Создать' : '<i class="fas fa-save"></i> Сохранить', [
                    'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'
                ]) ?>
                <?= Html::a('<i class="fas fa-times"></i> Отмена', ['index'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$js = <<<JS
    // Инициализация Select2 для удобного выбора
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });
    
    // Удаление изображения
    $('#remove-image').change(function() {
        if($(this).is(':checked')) {
            $('#product-imagefile').prop('disabled', true);
        } else {
            $('#product-imagefile').prop('disabled', false);
        }
    });
JS;
$this->registerJs($js);
?>