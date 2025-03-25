<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var \yii\web\View $this */
/** @var \app\models\Order $model */

$this->title = 'Оформление заказа';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="order-create-page cart-wrapper my-5">
    <h1 class="section-title mb-4"><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-7">
            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'email')->input('email') ?>
                </div>
            </div>

            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'address')->textarea(['rows' => 2]) ?>

            <?= $form->field($model, 'delivery_method')->dropDownList([
                'courier' => 'Курьерская доставка',
                'pickup' => 'Самовывоз',
            ], ['prompt' => 'Выберите способ доставки']) ?>

            <?= $form->field($model, 'payment_method')->dropDownList([
                'card' => 'Банковская карта',
                'cash' => 'Наличные курьеру',
                'online' => 'Онлайн-оплата',
            ], ['prompt' => 'Выберите способ оплаты']) ?>

            <?= $form->field($model, 'comment')->textarea(['rows' => 3]) ?>

            <div class="form-group mt-4">
                <?= Html::submitButton('Оформить заказ', ['class' => 'btn btn-primary btn-lg']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>

        <div class="col-lg-5">
            <div class="p-3 bg-light border rounded">
                <h5 class="mb-3">Ваш заказ</h5>
                <p class="mb-1">Количество товаров: <strong><?= Yii::$app->cart->getTotalCount() ?></strong></p>
                <p class="mb-1">Сумма заказа: <strong><?= Yii::$app->formatter->asCurrency(Yii::$app->cart->getTotalSum(), 'RUB') ?></strong></p>
                <p class="mb-1">Доставка: <strong><?= Yii::$app->cart->getTotalSum() >= 3000 ? 'Бесплатно' : '300 ₽' ?></strong></p>
                <hr>
                <p class="mb-0">Итого к оплате: <strong>
                    <?= Yii::$app->formatter->asCurrency(
                        Yii::$app->cart->getTotalSum() + (Yii::$app->cart->getTotalSum() >= 3000 ? 0 : 300),
                        'RUB'
                    ) ?>
                </strong></p>
            </div>
        </div>
    </div>
</div>