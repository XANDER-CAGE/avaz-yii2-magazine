<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var $items array */
/** @var $total float */

$this->title = 'Корзина';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="cart-page mt-5">
    <h1 class="text-center mb-5">Корзина</h1>
    
    <?php if (empty($items)): ?>
        <div class="empty-cart text-center">
            <div class="empty-cart-icon mb-4">
                <i class="fas fa-shopping-cart fa-4x text-muted"></i>
            </div>
            <h3 class="mb-4">Ваша корзина пуста</h3>
            <p class="mb-4">Добавьте товары в корзину, чтобы продолжить покупки</p>
            <?= Html::a('Перейти в каталог', ['/product/index'], ['class' => 'btn btn-primary btn-lg']) ?>
        </div>
    <?php else: ?>
        <div class="cart-content">
            <div class="row">
                <div class="col-lg-8">
                    <div class="cart-items card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Товары в корзине</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="border-0">Товар</th>
                                            <th class="border-0">Цена</th>
                                            <th class="border-0">Количество</th>
                                            <th class="border-0">Сумма</th>
                                            <th class="border-0"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($items as $item): ?>
                                            <tr>
                                                <td class="align-middle">
                                                    <div class="d-flex align-items-center">
                                                        <?php if (isset($item['product']->image) && $item['product']->image): ?>
                                                            <img src="<?= $item['product']->image ?>" alt="<?= Html::encode($item['product']->name) ?>" class="img-fluid rounded" style="max-width: 60px;">
                                                        <?php else: ?>
                                                            <img src="/img/no-image.jpg" alt="Изображение отсутствует" class="img-fluid rounded" style="max-width: 60px;">
                                                        <?php endif; ?>
                                                        <div class="ml-3">
                                                            <h6 class="mb-0"><?= Html::encode($item['product']->name) ?></h6>
                                                            <?php if (isset($item['product']->category) && $item['product']->category): ?>
                                                                <small class="text-muted"><?= Html::encode($item['product']->category->name) ?></small>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="align-middle"><?= Yii::$app->formatter->asCurrency($item['product']->price, 'RUB') ?></td>
                                                <td class="align-middle">
                                                    <div class="quantity">
                                                        <div class="input-group input-group-sm" style="width: 120px;">
                                                            <div class="input-group-prepend">
                                                                <button class="btn btn-outline-secondary quantity-down" type="button" data-id="<?= $item['product']->id ?>">-</button>
                                                            </div>
                                                            <input type="number" class="form-control text-center quantity-input" value="<?= $item['quantity'] ?>" min="1" data-id="<?= $item['product']->id ?>">
                                                            <div class="input-group-append">
                                                                <button class="btn btn-outline-secondary quantity-up" type="button" data-id="<?= $item['product']->id ?>">+</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="align-middle item-sum" data-id="<?= $item['product']->id ?>"><?= Yii::$app->formatter->asCurrency($item['sum'], 'RUB') ?></td>
                                                <td class="align-middle">
                                                    <?= Html::a('<i class="fas fa-times"></i>', ['cart/remove', 'id' => $item['product']->id], [
                                                        'class' => 'btn btn-sm btn-outline-danger remove-item',
                                                        'data-id' => $item['product']->id,
                                                        'title' => 'Удалить'
                                                    ]) ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-white d-flex justify-content-between">
                            <?= Html::a('Продолжить покупки', ['/product/index'], ['class' => 'btn btn-outline-secondary']) ?>
                            <?= Html::a('Очистить корзину', ['cart/clear'], [
                                'class' => 'btn btn-outline-danger',
                                'data' => [
                                    'confirm' => 'Вы уверены, что хотите очистить корзину?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card cart-summary">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Итого</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <span>Сумма:</span>
                                <span id="cart-subtotal"><?= Yii::$app->formatter->asCurrency($total, 'RUB') ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Доставка:</span>
                                <span>Бесплатно</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Итого к оплате:</strong>
                                <strong id="cart-total"><?= Yii::$app->formatter->asCurrency($total, 'RUB') ?></strong>
                            </div>
                            <?= Html::a('Оформить заказ', ['order/create'], ['class' => 'btn btn-primary btn-block']) ?>
                        </div>
                    </div>
                    
                    <div class="card mt-3">
                        <div class="card-body">
                            <h5 class="mb-3">Способы оплаты</h5>
                            <div class="payment-methods">
                                <div class="mb-2"><i class="far fa-credit-card mr-2"></i> Банковские карты</div>
                                <div class="mb-2"><i class="fab fa-apple-pay mr-2"></i> Apple Pay</div>
                                <div class="mb-2"><i class="fab fa-google-pay mr-2"></i> Google Pay</div>
                                <div><i class="fas fa-money-bill-wave mr-2"></i> Наличными при получении</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
$updateCartUrl = Url::to(['cart/update-quantity']);
$removeItemUrl = Url::to(['cart/remove']);

$js = <<<JS
    // Функционал изменения количества товара
    $('.quantity-down').on('click', function() {
        var input = $(this).closest('.input-group').find('.quantity-input');
        var value = parseInt(input.val()) - 1;
        if (value >= 1) {
            input.val(value);
            updateQuantity(input.data('id'), value);
        }
    });
    
    $('.quantity-up').on('click', function() {
        var input = $(this).closest('.input-group').find('.quantity-input');
        var value = parseInt(input.val()) + 1;
        input.val(value);
        updateQuantity(input.data('id'), value);
    });
    
    $('.quantity-input').on('change', function() {
        var value = parseInt($(this).val());
        if (value < 1) {
            $(this).val(1);
            value = 1;
        }
        updateQuantity($(this).data('id'), value);
    });
    
    $('.remove-item').on('click', function(e) {
        e.preventDefault();
        var itemId = $(this).data('id');
        if (confirm('Вы уверены, что хотите удалить этот товар из корзины?')) {
            $.ajax({
                url: '{$removeItemUrl}',
                type: 'POST',
                data: {id: itemId},
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Произошла ошибка при удалении товара.');
                    }
                },
                error: function() {
                    alert('Произошла ошибка при обращении к серверу.');
                }
            });
        }
    });
    
    function updateQuantity(itemId, quantity) {
        $.ajax({
            url: '{$updateCartUrl}',
            type: 'POST',
            data: {id: itemId, quantity: quantity},
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Обновляем сумму товара
                    $('.item-sum[data-id="' + itemId + '"]').text(response.item_sum);
                    
                    // Обновляем общую сумму
                    $('#cart-subtotal').text(response.subtotal);
                    $('#cart-total').text(response.total);
                    
                    // Обновляем количество товаров в шапке
                    $('.cart-count').text(response.count);
                } else {
                    alert('Произошла ошибка при обновлении количества.');
                }
            },
            error: function() {
                alert('Произошла ошибка при обращении к серверу.');
            }
        });
    }
JS;

$css = <<<CSS
    .cart-page {
        margin-bottom: 60px;
    }
    
    .empty-cart {
        padding: 60px 0;
    }
    
    .empty-cart-icon {
        color: #e0e0e0;
    }
    
    .cart-items .table th,
    .cart-items .table td {
        padding: 1rem;
    }
    
    .quantity .form-control {
        text-align: center;
    }
    
    .cart-summary {
        position: sticky;
        top: 20px;
    }
    
    .payment-methods {
        color: #666;
        font-size: 14px;
    }
    
    .btn-primary {
        background-color: #a38e65;
        border-color: #a38e65;
    }
    
    .btn-primary:hover {
        background-color: #8a7656;
        border-color: #8a7656;
    }
    
    .btn-outline-secondary {
        color: #a38e65;
        border-color: #a38e65;
    }
    
    .btn-outline-secondary:hover {
        background-color: #a38e65;
        border-color: #a38e65;
        color: white;
    }
CSS;

$this->registerCss($css);
$this->registerJs($js);
?>