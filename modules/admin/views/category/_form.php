<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Category;

/** @var $form yii\bootstrap4\ActiveForm */
/** @var $model app\models\Category */

?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'parent_id')->dropDownList(
        ArrayHelper::map(Category::find()->where(['!=', 'id', $model->id])->all(), 'id', 'name'),
        ['prompt' => 'Без родителя']
    ) ?>

    <?= $form->field($model, 'status')->dropDownList([1 => 'Активна', 0 => 'Неактивна']) ?>

    <div class="form-group mt-3">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
