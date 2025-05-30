<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var \app\models\Product $product */

// Проверяем, является ли товар новинкой (создан за последние 30 дней)
$isNew = isset($product->created_at) && strtotime($product->created_at) > strtotime('-30 days');

// Проверяем наличие скидки (здесь можно добавить логику расчета скидки)
$hasDiscount = $product->price > 0; // Пример условия для скидки
$discountPercent = $hasDiscount ? 30 : 0; // Пример скидки 30%
$originalPrice = $hasDiscount ? $product->price * 1.3 : null;

// Получаем рейтинг товара (если есть система рейтингов)
$rating = 4.5; // Заглушка для рейтинга
$reviewsCount = 15; // Заглушка для количества отзывов
?>

<div class="product-card h-100">
    <div class="product-image-container">
        <?= Html::img(
            $product->image ?: '/img/no-image.jpg', 
            [
                'alt' => Html::encode($product->name),
                'class' => 'product-img',
                'loading' => 'lazy'
            ]
        ) ?>
        
        <!-- Badges -->
        <div class="product-badges">
            <?php if ($isNew): ?>
                <div class="product-badge badge-new">
                    <i class="fas fa-star"></i>
                    Новинка
                </div>
            <?php endif; ?>
            
            <?php if ($hasDiscount): ?>
                <div class="product-badge badge-sale">
                    -<?= $discountPercent ?>%
                </div>
            <?php endif; ?>
            
            <?php if (!$product->price): ?>
                <div class="product-badge badge-custom">
                    По запросу
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Quick Actions Overlay -->
        <div class="product-overlay">
            <div class="overlay-actions">
                <?= Html::a(
                    '<i class="fas fa-eye"></i>', 
                    ['/product/view', 'id' => $product->id], 
                    [
                        'class' => 'btn btn-overlay btn-quick-view',
                        'title' => 'Быстрый просмотр',
                        'data-bs-toggle' => 'tooltip'
                    ]
                ) ?>
                
                <?= Html::a(
                    '<i class="far fa-heart"></i>', 
                    '#', 
                    [
                        'class' => 'btn btn-overlay btn-wishlist',
                        'title' => 'В избранное',
                        'data-product-id' => $product->id,
                        'data-bs-toggle' => 'tooltip'
                    ]
                ) ?>
                
                <?= Html::a(
                    '<i class="fas fa-balance-scale"></i>', 
                    '#', 
                    [
                        'class' => 'btn btn-overlay btn-compare',
                        'title' => 'Сравнить',
                        'data-product-id' => $product->id,
                        'data-bs-toggle' => 'tooltip'
                    ]
                ) ?>
            </div>
        </div>
    
    </div>
    
    <div class="product-info">
        <!-- Category -->
        <?php if (isset($product->category) && $product->category): ?>
            <div class="product-category">
                <?= Html::a(
                    Html::encode($product->category->name),
                    ['/product/index', 'category_id' => $product->category->id],
                    ['class' => 'category-link']
                ) ?>
            </div>
        <?php endif; ?>
        
        <!-- Product Title -->
        <h5 class="product-title">
            <?= Html::a(
                Html::encode($product->name), 
                ['/product/view', 'id' => $product->id],
                ['class' => 'product-link', 'title' => Html::encode($product->name)]
            ) ?>
        </h5>
        
        <!-- Rating -->
        <div class="product-rating">
            <div class="rating-stars">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <?php if ($i <= floor($rating)): ?>
                        <i class="fas fa-star"></i>
                    <?php elseif ($i - 0.5 <= $rating): ?>
                        <i class="fas fa-star-half-alt"></i>
                    <?php else: ?>
                        <i class="far fa-star"></i>
                    <?php endif; ?>
                <?php endfor; ?>
                <span class="rating-value"><?= $rating ?></span>
            </div>
            <div class="rating-reviews">
                (<?= $reviewsCount ?> отзывов)
            </div>
        </div>
        
        <!-- Features/Attributes -->
        <?php if (isset($product->features) && !empty($product->features)): ?>
            <div class="product-features">
                <?php 
                $features = is_array($product->features) ? array_slice($product->features, 0, 3) : [];
                foreach ($features as $feature):
                ?>
                    <span class="feature-tag"><?= Html::encode($feature) ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <!-- Price Section -->
        <div class="product-price-section">
            <?php if ($product->price): ?>
                <div class="price-container">
                    <?php if ($hasDiscount && $originalPrice): ?>
                        <span class="price-old"><?= Yii::$app->formatter->asCurrency($originalPrice, 'RUB') ?></span>
                    <?php endif; ?>
                    <span class="price-current"><?= Yii::$app->formatter->asCurrency($product->price, 'RUB') ?></span>
                </div>
                
                <?php if ($hasDiscount): ?>
                    <div class="price-save">
                        Экономия: <?= Yii::$app->formatter->asCurrency($originalPrice - $product->price, 'RUB') ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="price-request">
                    <i class="fas fa-phone me-2"></i>
                    Цена по запросу
                </div>
            <?php endif; ?>
        </div>
        <!-- Action Buttons -->
        <div class="product-actions">
                <div class="action-buttons">
                    <?= Html::a(
                        '<i class="fas fa-shopping-cart me-2"></i>В корзину', 
                        ['/cart/add', 'id' => $product->id], 
                        [
                            'class' => 'btn btn-add-cart add-to-cart',
                            'data-id' => $product->id,
                            'data-name' => Html::encode($product->name)
                        ]
                    ) ?>
                    
                    <div class="secondary-actions">
                        <?= Html::a(
                            '<i class="fas fa-bolt"></i>', 
                            ['/order/quick-buy', 'id' => $product->id], 
                            [
                                'class' => 'btn btn-quick-buy',
                                'title' => 'Купить в 1 клик',
                                'data-bs-toggle' => 'tooltip'
                            ]
                        ) ?>
                    </div>
                </div>
        </div>
    </div>
</div>

<style>
.product-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border: 1px solid #f1f5f9;
    position: relative;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.product-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

/* Image Container */
.product-image-container {
    position: relative;
    overflow: hidden;
    height: 250px;
    background: #f8fafc;
}

.product-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.5s ease;
}

.product-card:hover .product-img {
    transform: scale(1.1);
}

/* Badges */
.product-badges {
    position: absolute;
    top: 15px;
    right: 15px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    z-index: 3;
}

.product-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    text-align: center;
    color: white;
    display: flex;
    align-items: center;
    gap: 4px;
    backdrop-filter: blur(10px);
}

.badge-new {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    animation: pulse 2s infinite;
}

.badge-sale {
    background: linear-gradient(135deg, #f5576c 0%, #ff6b6b 100%);
}

.badge-custom {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Overlay */
.product-overlay {
    position: absolute;
    top: 15px;
    left: 15px;
    opacity: 0;
    transition: all 0.3s ease;
    z-index: 2;
}

.product-card:hover .product-overlay {
    opacity: 1;
}

.overlay-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.btn-overlay {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    color: #64748b;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.btn-overlay:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: scale(1.1);
    text-decoration: none;
}

/* Stock Indicator */
.stock-indicator {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0,0,0,0.8);
    color: white;
    padding: 8px 12px;
    font-size: 0.75rem;
}

.stock-bar {
    height: 3px;
    background: rgba(255,255,255,0.3);
    border-radius: 2px;
    margin-bottom: 4px;
}

.stock-progress {
    height: 100%;
    background: linear-gradient(135deg, #f5576c 0%, #ff6b6b 100%);
    border-radius: 2px;
    transition: width 0.3s ease;
}

.stock-text {
    margin: 0;
    font-weight: 600;
}

/* Product Info */
.product-info {
    padding: 1.5rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.product-category {
    margin-bottom: 0.5rem;
}

.category-link {
    font-size: 0.8rem;
    color: #64748b;
    text-decoration: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
    transition: color 0.3s ease;
}

.category-link:hover {
    color: #667eea;
    text-decoration: none;
}

.product-title {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    line-height: 1.4;
    min-height: 2.8rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-link {
    color: #2d3748;
    text-decoration: none;
    transition: color 0.3s ease;
}

.product-link:hover {
    color: #667eea;
    text-decoration: none;
}

/* Rating */
.product-rating {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.75rem;
    font-size: 0.85rem;
}

.rating-stars {
    display: flex;
    align-items: center;
    gap: 2px;
}

.rating-stars i {
    color: #fbbf24;
    font-size: 0.9rem;
}

.rating-value {
    margin-left: 6px;
    font-weight: 600;
    color: #4a5568;
}

.rating-reviews {
    color: #64748b;
    font-size: 0.8rem;
}

/* Features */
.product-features {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 1rem;
}

.feature-tag {
    background: #f1f5f9;
    color: #64748b;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 500;
}

/* Price Section */
.product-price-section {
    margin-bottom: 1rem;
}

.price-container {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 0.25rem;
}

.price-current {
    font-size: 1.4rem;
    font-weight: 800;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.price-old {
    font-size: 1rem;
    color: #a0aec0;
    text-decoration: line-through;
    font-weight: 500;
}

.price-save {
    font-size: 0.8rem;
    color: #48bb78;
    font-weight: 600;
}

.price-request {
    font-size: 1.1rem;
    color: #667eea;
    font-weight: 600;
    display: flex;
    align-items: center;
}

/* Availability */
.product-availability {
    margin-bottom: 1rem;
}

.availability-status {
    font-size: 0.85rem;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.availability-status.available {
    color: #48bb78;
}

.availability-status.unavailable {
    color: #f56565;
}

/* Action Buttons */
.product-actions {
    margin-top: auto;
}

.action-buttons {
    display: flex;
    gap: 8px;
    align-items: center;
}

.btn-add-cart {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    padding: 12px 20px;
    border-radius: 25px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 1;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    position: relative;
    overflow: hidden;
}

.btn-add-cart:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    color: white;
    text-decoration: none;
}

.btn-add-cart.btn-success {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
}

.btn-add-cart:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.btn-quick-buy {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #f5576c 0%, #ff6b6b 100%);
    border: none;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-quick-buy:hover {
    transform: scale(1.1);
    color: white;
    text-decoration: none;
}

.btn-notify {
    background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
    border: none;
    color: white;
    padding: 12px 20px;
    border-radius: 25px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.btn-notify:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(74, 144, 226, 0.4);
    color: white;
    text-decoration: none;
}

/* Animations */
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

/* Responsive */
@media (max-width: 768px) {
    .product-card {
        margin-bottom: 1.5rem;
    }
    
    .product-image-container {
        height: 200px;
    }
    
    .product-info {
        padding: 1rem;
    }
    
    .product-title {
        font-size: 1rem;
        min-height: 2.4rem;
    }
    
    .price-current {
        font-size: 1.2rem;
    }
    
    .btn-add-cart {
        padding: 10px 16px;
        font-size: 0.85rem;
    }
    
    .product-rating {
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
    }
}

@media (max-width: 576px) {
    .overlay-actions {
        flex-direction: row;
        gap: 6px;
    }
    
    .btn-overlay {
        width: 35px;
        height: 35px;
    }
    
    .product-badges {
        top: 10px;
        right: 10px;
    }
    
    .product-overlay {
        top: 10px;
        left: 10px;
    }
}
</style>