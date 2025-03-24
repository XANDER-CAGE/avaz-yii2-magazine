<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

$this->title = 'Оформление заказа';
$this->params['breadcrumbs'][] = ['label' => 'Корзина', 'url' => ['cart/index']];
$this->params['breadcrumbs'][] = $this->title;

// Получаем данные корзины
$cartItems = Yii::$app->cart->getProductData();
$cartTotal = Yii::$app->cart->getTotalSum();
?>

<div class="order-page mt-5">
    <h1 class="text-center mb-5">Оформление заказа</h1>
    
    <div class="row">
        <div class="col-lg-7">
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Данные для доставки</h5>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin([
                        'id' => 'order-form',
                        'enableClientValidation' => true,
                    ]); ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Иван Иванов']) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'email')->input('email', ['placeholder' => 'example@mail.ru']) ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => '+7 (___) ___-__-__']) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'delivery_method')->dropDownList([
                                'courier' => 'Курьерская доставка',
                                'pickup' => 'Самовывоз из галереи',
                                'international' => 'Международная доставка',
                            ], ['prompt' => 'Выберите способ доставки']) ?>
                        </div>
                    </div>
                    
                    <?= $form->field($model, 'address')->textarea(['rows' => 3, 'placeholder' => 'Город, улица, дом, квартира, индекс']) ?>
                    
                    <?= $form->field($model, 'comment')->textarea(['rows' => 3, 'placeholder' => 'Дополнительная информация для курьера или особые пожелания']) ?>
                    
                    <div class="form-group mt-4">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="terms-agreement" required>
                            <label class="custom-control-label" for="terms-agreement">
                                Я согласен с <a href="#">условиями обработки персональных данных</a> и <a href="#">правилами покупки</a>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Способ оплаты</h5>
                </div>
                <div class="card-body">
                    <?= $form->field($model, 'payment_method')->radioList([
                        'card' => 'Банковская карта онлайн',
                        'cash' => 'Наличными при получении',
                        'bank' => 'Банковский перевод',
                    ])->label(false) ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-5">
            <div class="card order-summary">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Ваш заказ</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                <?php foreach ($cartItems as $item): ?>
                                    <tr>
                                        <td class="align-middle border-0">
                                            <div class="d-flex align-items-center">
                                                <?php if (isset($item['product']->image) && $item['product']->image): ?>
                                                    <img src="<?= $item['product']->image ?>" alt="<?= Html::encode($item['product']->name) ?>" class="img-fluid rounded" style="max-width: 50px;">
                                                <?php else: ?>
                                                    <img src="/img/no-image.jpg" alt="Изображение отсутствует" class="img-fluid rounded" style="max-width: 50px;">
                                                <?php endif; ?>
                                                <div class="ml-3">
                                                    <h6 class="mb-0 small"><?= Html::encode($item['product']->name) ?></h6>
                                                    <small class="text-muted">Кол-во: <?= $item['quantity'] ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle text-right border-0">
                                            <span class="font-weight-bold"><?= Yii::$app->formatter->asCurrency($item['sum'], 'RUB') ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <hr class="m-0">
                    <div class="p-4">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Сумма:</span>
                            <span><?= Yii::$app->formatter->asCurrency($cartTotal, 'RUB') ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Доставка:</span>
                            <span>Бесплатно</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <strong>Итого к оплате:</strong>
                            <strong><?= Yii::$app->formatter->asCurrency($cartTotal, 'RUB') ?></strong>
                        </div>
                        
                        <?= Html::submitButton('Подтвердить заказ', ['class' => 'btn btn-primary btn-block btn-lg']) ?>
                        
                        <div class="text-center mt-3">
                            <small class="text-muted">Нажимая кнопку "Подтвердить заказ", вы соглашаетесь с условиями покупки</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-body">
                    <div class="secure-payment">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-lock mr-2"></i>
                            <span>Безопасная оплата</span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-shipping-fast mr-2"></i>
                            <span>Доставка по всему миру</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-shield-alt mr-2"></i>
                            <span>Гарантия подлинности</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php ActiveForm::end(); ?>
</div>

<?php
$css = <<<CSS
    .order-page {
        margin-bottom: 60px;
    }
    
    .custom-control-label a {
        color: #a38e65;
        text-decoration: underline;
    }
    
    .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #a38e65;
        border-color: #a38e65;
    }
    
    .order-summary {
        position: sticky;
        top: 20px;
    }
    
    .secure-payment {
        color: #666;
        font-size: 14px;
    }
    
    .secure-payment i {
        color: #a38e65;
    }
    
    .btn-primary {
        background-color: #a38e65;
        border-color: #a38e65;
    }
    
    .btn-primary:hover {
        background-color: #8a7656;
        border-color: #8a7656;
    }
    
    .form-control:focus {
        border-color: #a38e65;
        box-shadow: 0 0 0 0.2rem rgba(163, 142, 101, 0.25);
    }
    
    .custom-radio .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #a38e65;
        border-color: #a38e65;
    }
CSS;

$js = <<<JS
    // Маска для телефона
    if (typeof IMask !== 'undefined') {
        var phoneInput = document.getElementById('order-phone');
        if (phoneInput) {
            IMask(phoneInput, {
                mask: '+{7} (000) 000-00-00'
            });
        }
    }
    
    // Показать/скрыть поле адреса в зависимости от выбранного способа доставки
    $('#order-delivery_method').on('change', function() {
        var deliveryMethod = $(this).val();
        var addressField = $('#order-address').closest('.form-group');
        
        if (deliveryMethod === 'pickup') {
            addressField.hide();
        } else {
            addressField.show();
        }
    });
    
    // Проверка согласия с условиями
    $('#order-form').on('submit', function(e) {
        if (!$('#terms-agreement').prop('checked')) {
            e.preventDefault();
            alert('Необходимо согласиться с условиями обработки персональных данных и правилами покупки');
            return false;
        }
        return true;
    });
JS;

$this->registerCss($css);
$this->registerJs($js);
?>