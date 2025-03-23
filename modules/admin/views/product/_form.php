<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Category;

/** @var $this yii\web\View */
/** @var $model app\models\Product */
/** @var $form yii\bootstrap4\ActiveForm */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?= $form->field($model, 'category_id')->dropDownList(
        ArrayHelper::map(Category::find()->all(), 'id', 'name'),
        ['prompt' => 'Выберите категорию']
    ) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->input('number', ['step' => '0.01']) ?>

    <?= $form->field($model, 'short_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'full_description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->dropDownList([1 => 'Активен', 0 => 'Неактивен']) ?>

    <?php if ($model->image): ?>
        <div class="mb-3">
            <label>Текущее изображение:</label><br>
            <img src="<?= Html::encode($model->image) ?>" style="max-width:150px">
        </div>
    <?php endif; ?>

    <?= $form->field($model, 'imageFile')->fileInput() ?>

    <div class="form-group mt-3">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
