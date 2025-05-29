<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/** @var yii\web\View $this */
/** @var array $items */
/** @var float $subtotal */
/** @var float $deliveryCost */
/** @var float $deliveryThreshold */
/** @var float $total */

$this->title = 'Корзина';
$this->params['breadcrumbs'][] = $this->title;

// Регистрируем CSS
$this->registerCss("
.cart-page {
    min-height: 60vh;
}

.cart-item {
    transition: all 0.3s ease;
}

.cart-item:hover {
    background-color: #f8f9fa;
}

.quantity-controls {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.quantity-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 1px solid #dee2e6;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.quantity-btn:hover {
    background: #007bff;
    color: white;
    border-color: #007bff;
}

.quantity-input {
    width: 60px;
    text-align: center;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 0.25rem;
}

.product-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
}

.cart-summary {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.price-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #dee2e6;
}

.price-row:last-child {
    border-bottom: none;
    font-weight: bold;
    font-size: 1.2rem;
}

.btn-checkout {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    padding: 15px 30px;
    border-radius: 25px;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    width: 100%;
    text-align: center;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-checkout:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    color: white;
    text-decoration: none;
}

.empty-cart {
    text-align: center;
    padding: 4rem 2rem;
    color: #6c757d;
}

.empty-cart i {
    font-size: 4rem;
    margin-bottom: 1.5rem;
    opacity: 0.5;
}

.delivery-info {
    background: #e3f2fd;
    border-left: 4px solid #2196f3;
    padding: 1rem;
    border-radius: 0 8px 8px 0;
    margin-bottom: 1rem;
}

.discount-badge {
    background: linear-gradient(135deg, #ff6b6b, #ee5a24);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
}

.loading {
    opacity: 0.6;
    pointer-events: none;
}

@media (max-width: 768px) {
    .cart-table {
        font-size: 0.9rem;
    }
    
    .product-image {
        width: 60px;
        height: 60px;
    }
    
    .cart-summary {
        margin-top: 2rem;
    }
}
");
?>

<div class="container my-5">
    <div class="cart-page">
        <!-- Заголовок -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="section-title">
                <i class="fas fa-shopping-cart me-3"></i>Корзина покупок
            </h1>
            <?php if (!empty($items)): ?>
                <div class="text-muted">
                    Товаров: <strong id="items-count"><?= count($items) ?></strong>
                </div>
            <?php endif; ?>
        </div>

        <?php if (empty($items)): ?>
            <!-- Пустая корзина -->
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <h3>Ваша корзина пуста</h3>
                <p class="mb-4">Добавьте товары из каталога, чтобы сделать покупку</p>
                <?= Html::a(
                    '<i class="fas fa-arrow-left me-2"></i>Перейти к покупкам', 
                    ['/product/index'], 
                    ['class' => 'btn btn-primary btn-lg']
                ) ?>
            </div>
        <?php else: ?>
            <div class="row">
                <!-- Список товаров -->
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <!-- Информация о доставке -->
                            <?php if ($subtotal < $deliveryThreshold): ?>
                                <div class="delivery-info">
                                    <i class="fas fa-truck me-2"></i>
                                    <strong>Добавьте товаров на <?= Yii::$app->formatter->asCurrency($deliveryThreshold - $subtotal, 'RUB') ?> для бесплатной доставки!</strong>
                                </div>
                            <?php else: ?>
                                <div class="delivery-info" style="background: #e8f5e8; border-left-color: #4caf50;">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <strong>Поздравляем! Вы получили бесплатную доставку!</strong>
                                </div>
                            <?php endif; ?>

                            <!-- Таблица товаров -->
                            <div class="table-responsive">
                                <table class="table cart-table mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 50%;">Товар</th>
                                            <th class="text-center" style="width: 15%;">Цена</th>
                                            <th class="text-center" style="width: 20%;">Количество</th>
                                            <th class="text-center" style="width: 15%;">Сумма</th>
                                            <th class="text-center" style="width: 5%;">Удалить</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cart-items">
                                        <?php foreach ($items as $item): ?>
                                            <?php 
                                            $product = $item['product'];
                                            $quantity = $item['quantity'];
                                            $sum = $item['sum'];
                                            ?>
                                            <tr class="cart-item" data-id="<?= $product->id ?>">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="<?= $product->getImageUrl() ?>" 
                                                             alt="<?= Html::encode($product->name) ?>" 
                                                             class="product-image me-3">
                                                        <div>
                                                            <h6 class="mb-1">
                                                                <?= Html::a(
                                                                    Html::encode($product->name),
                                                                    ['/product/view', 'id' => $product->id],
                                                                    ['class' => 'text-decoration-none']
                                                                ) ?>
                                                            </h6>
                                                            <?php if (!empty($product->sku)): ?>
                                                                <small class="text-muted">Артикул: <?= Html::encode($product->sku) ?></small>
                                                            <?php endif; ?>
                                                            
                                                            <?php if ($product->isOnSale()): ?>
                                                                <div class="mt-1">
                                                                    <span class="discount-badge">
                                                                        Скидка <?= $product->getDiscountPercent() ?>%
                                                                    </span>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <div class="price-info">
                                                        <?php if ($product->isOnSale()): ?>
                                                            <div class="text-muted text-decoration-line-through small">
                                                                <?= $product->getFormattedOldPrice() ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        <strong class="product-price">
                                                            <?= $product->getFormattedPrice() ?>
                                                        </strong>
                                                    </div>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <div class="quantity-controls">
                                                        <button type="button" class="quantity-btn" 
                                                                onclick="updateQuantity(<?= $product->id ?>, <?= max(1, $quantity - 1) ?>)"
                                                                <?= $quantity <= 1 ? 'disabled' : '' ?>>
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                        <input type="number" 
                                                               class="quantity-input" 
                                                               value="<?= $quantity ?>" 
                                                               min="1" 
                                                               max="<?= $product->hasStock() ? $product->stock : 999 ?>"
                                                               onchange="updateQuantity(<?= $product->id ?>, this.value)">
                                                        <button type="button" class="quantity-btn" 
                                                                onclick="updateQuantity(<?= $product->id ?>, <?= $quantity + 1 ?>)"
                                                                <?= ($product->hasStock() && $quantity >= $product->stock) ? 'disabled' : '' ?>>
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                    <?php if ($product->hasStock()): ?>
                                                        <small class="text-muted d-block mt-1">
                                                            В наличии: <?= $product->stock ?> шт.
                                                        </small>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <strong class="item-sum">
                                                        <?= Yii::$app->formatter->asCurrency($sum, 'RUB') ?>
                                                    </strong>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <button type="button" 
                                                            class="btn btn-outline-danger btn-sm" 
                                                            onclick="removeItem(<?= $product->id ?>)"
                                                            title="Удалить товар">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Дополнительные действия -->
                    <div class="mt-3 d-flex justify-content-between">
                        <?= Html::a(
                            '<i class="fas fa-arrow-left me-2"></i>Продолжить покупки',
                            ['/product/index'],
                            ['class' => 'btn btn-outline-primary']
                        ) ?>
                        
                        <button type="button" class="btn btn-outline-secondary" onclick="clearCart()">
                            <i class="fas fa-trash me-2"></i>Очистить корзину
                        </button>
                    </div>
                </div>

                <!-- Итоговая информация -->
                <div class="col-lg-4">
                    <div class="cart-summary">
                        <h5 class="mb-4">
                            <i class="fas fa-calculator me-2"></i>Итоги заказа
                        </h5>
                        
                        <div class="price-breakdown mb-4">
                            <div class="price-row">
                                <span>Товары (<?= count($items) ?>):</span>
                                <span id="subtotal"><?= Yii::$app->formatter->asCurrency($subtotal, 'RUB') ?></span>
                            </div>
                            
                            <div class="price-row">
                                <span>
                                    Доставка:
                                    <?php if ($deliveryCost == 0): ?>
                                        <i class="fas fa-gift text-success ms-1" title="Бесплатная доставка"></i>
                                    <?php endif; ?>
                                </span>
                                <span id="delivery-cost">
                                    <?= $deliveryCost == 0 ? 'Бесплатно' : Yii::$app->formatter->asCurrency($deliveryCost, 'RUB') ?>
                                </span>
                            </div>
                            
                            <?php if ($subtotal < $deliveryThreshold && $deliveryCost > 0): ?>
                                <div class="price-row small text-muted">
                                    <span>До бесплатной доставки:</span>
                                    <span><?= Yii::$app->formatter->asCurrency($deliveryThreshold - $subtotal, 'RUB') ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="price-row">
                                <span>Итого:</span>
                                <span id="total"><?= Yii::$app->formatter->asCurrency($total, 'RUB') ?></span>
                            </div>
                        </div>

                        <!-- Кнопка оформления заказа -->
                        <?= Html::a(
                            '<i class="fas fa-credit-card me-2"></i>Оформить заказ',
                            ['/cart/checkout'],
                            ['class' => 'btn-checkout']
                        ) ?>

                        <!-- Дополнительная информация -->
                        <div class="mt-4">
                            <div class="small text-muted">
                                <div class="mb-2">
                                    <i class="fas fa-shield-alt me-2 text-success"></i>
                                    Безопасная оплата
                                </div>
                                <div class="mb-2">
                                    <i class="fas fa-undo me-2 text-info"></i>
                                    30 дней на возврат
                                </div>
                                <div>
                                    <i class="fas fa-headset me-2 text-primary"></i>
                                    Поддержка 24/7
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Рекомендуемые товары -->
                    <?php if (!empty($recommendedProducts)): ?>
                        <div class="mt-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-star me-2"></i>Рекомендуем также
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <?php foreach ($recommendedProducts as $product): ?>
                                        <div class="d-flex align-items-center mb-3">
                                            <img src="<?= $product->getThumbnailUrl('60x60') ?>" 
                                                 alt="<?= Html::encode($product->name) ?>" 
                                                 class="me-3" 
                                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1" style="font-size: 0.9rem;">
                                                    <?= Html::a(
                                                        Html::encode($product->name),
                                                        ['/product/view', 'id' => $product->id],
                                                        ['class' => 'text-decoration-none']
                                                    ) ?>
                                                </h6>
                                                <div class="text-primary fw-bold" style="font-size: 0.9rem;">
                                                    <?= $product->getFormattedPrice() ?>
                                                </div>
                                            </div>
                                            <button class="btn btn-outline-primary btn-sm" 
                                                    onclick="addToCart(<?= $product->id ?>, '<?= Html::encode($product->name) ?>')">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
// Регистрируем JavaScript
$this->registerJs("
// Обновление количества товара
function updateQuantity(id, quantity) {
    quantity = Math.max(1, parseInt(quantity));
    
    const row = document.querySelector(`tr[data-id='${id}']`);
    const quantityInput = row.querySelector('.quantity-input');
    
    // Показываем состояние загрузки
    row.classList.add('loading');
    quantityInput.value = quantity;
    
    fetch('/cart/update-quantity', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-Token': document.querySelector('meta[name=csrf-token]').getAttribute('content')
        },
        body: `id=${id}&quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Обновляем сумму товара
            row.querySelector('.item-sum').textContent = data.item_sum;
            
            // Обновляем итоги
            document.getElementById('subtotal').textContent = data.subtotal;
            document.getElementById('delivery-cost').textContent = data.deliveryCost;
            document.getElementById('total').textContent = data.total;
            document.getElementById('items-count').textContent = data.count;
            
            showNotification('success', data.message);
        } else {
            // Возвращаем предыдущее значение при ошибке
            quantityInput.value = quantityInput.defaultValue;
            showNotification('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        quantityInput.value = quantityInput.defaultValue;
        showNotification('error', 'Произошла ошибка при обновлении количества');
    })
    .finally(() => {
        row.classList.remove('loading');
    });
}

// Удаление товара из корзины
function removeItem(id) {
    if (!confirm('Удалить товар из корзины?')) {
        return;
    }
    
    const row = document.querySelector(`tr[data-id='${id}']`);
    row.style.opacity = '0.5';
    
    fetch(`/cart/remove?id=${id}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Анимация удаления
            row.style.transform = 'translateX(100%)';
            row.style.transition = 'all 0.3s ease';
            
            setTimeout(() => {
                row.remove();
                
                // Обновляем итоги
                document.getElementById('subtotal').textContent = data.subtotal;
                document.getElementById('delivery-cost').textContent = data.deliveryCost;
                document.getElementById('total').textContent = data.total;
                document.getElementById('items-count').textContent = data.count;
                
                // Перезагружаем страницу, если корзина пуста
                if (data.count === 0) {
                    location.reload();
                }
            }, 300);
            
            showNotification('info', data.message);
        } else {
            row.style.opacity = '1';
            showNotification('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        row.style.opacity = '1';
        showNotification('error', 'Произошла ошибка при удалении товара');
    });
}

// Очистка корзины
function clearCart() {
    if (!confirm('Очистить всю корзину?')) {
        return;
    }
    
    fetch('/cart/clear', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-Token': document.querySelector('meta[name=csrf-token]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', data.message);
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showNotification('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Произошла ошибка при очистке корзины');
    });
}

// Добавление товара в корзину (для рекомендуемых товаров)
function addToCart(id, name) {
    fetch(`/cart/add?id=${id}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', `${name} добавлен в корзину`);
            // Можем обновить счетчики или перезагрузить страницу
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showNotification('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Произошла ошибка при добавлении товара');
    });
}

// Функция показа уведомлений
function showNotification(type, message) {
    // Создаем элемент уведомления
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show`;
    notification.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        <i class=\"fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle'} me-2\"></i>
        ${message}
        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Автоматическое скрытие через 4 секунды
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 4000);
}

// Обработка изменения количества через input
document.querySelectorAll('.quantity-input').forEach(input => {
    input.addEventListener('change', function() {
        const id = this.closest('tr').dataset.id;
        const quantity = parseInt(this.value);
        updateQuantity(id, quantity);
    });
    
    // Сохраняем исходное значение для возможности отката
    input.defaultValue = input.value;
});
");
?>