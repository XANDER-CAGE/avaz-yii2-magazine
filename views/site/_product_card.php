<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var \app\models\Product $product */
?>

<div class="product-card h-100">
    <div class="product-image">
        <?= Html::img(
            $product->image ?: '/img/no-image.jpg', 
            [
                'alt' => Html::encode($product->name),
                'class' => 'product-img'
            ]
        ) ?>
        
        <?php if (isset($product->created_at) && strtotime($product->created_at) > strtotime('-30 days')): ?>
            <div class="product-badge badge-new">Новинка</div>
        <?php endif; ?>
        
        <?php if ($product->price > 0): ?>
            <div class="product-badge" style="background: var(--danger-color); top: 15px; left: 15px;">-30%</div>
        <?php endif; ?>
        
        <div class="product-overlay">
            <?= Html::a(
                '<i class="fas fa-eye"></i>', 
                ['/product/view', 'id' => $product->id], 
                [
                    'class' => 'btn btn-overlay',
                    'title' => 'Быстрый просмотр'
                ]
            ) ?>
            <?= Html::a(
                '<i class="fas fa-heart"></i>', 
                '#', 
                [
                    'class' => 'btn btn-overlay',
                    'title' => 'В избранное',
                    'data-product-id' => $product->id
                ]
            ) ?>
        </div>
    </div>
    
    <div class="product-info">
        <?php if (isset($product->category) && $product->category): ?>
            <div class="product-category">
                <?= Html::encode($product->category->name) ?>
            </div>
        <?php endif; ?>
        
        <h5 class="product-title">
            <?= Html::a(
                Html::encode($product->name), 
                ['/product/view', 'id' => $product->id],
                ['class' => 'product-link']
            ) ?>
        </h5>
        
        <div class="product-price">
            <?php if ($product->price): ?>
                <span class="current-price"><?= Yii::$app->formatter->asCurrency($product->price, 'RUB') ?></span>
                <?php if ($product->price > 0): // Показываем старую цену если есть скидка ?>
                    <span class="old-price text-muted text-decoration-line-through ms-2">
                        <?= Yii::$app->formatter->asCurrency($product->price * 1.3, 'RUB') ?>
                    </span>
                <?php endif; ?>
            <?php else: ?>
                <span class="price-on-request">Цена по запросу</span>
            <?php endif; ?>
        </div>
        
        <div class="product-actions">
            <?= Html::a(
                '<i class="fas fa-shopping-cart me-2"></i>В корзину', 
                ['/cart/add', 'id' => $product->id], 
                [
                    'class' => 'btn btn-add-cart add-to-cart',
                    'data-id' => $product->id
                ]
            ) ?>
        </div>
    </div>
</div>

<style>
.product-card {
    background: white;
    border-radius: var(--radius-lg, 20px);
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border: 1px solid #f1f5f9;
    position: relative;
    display: flex;
    flex-direction: column;
}

.product-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 60px rgba(0,0,0,0.2);
}

.product-image {
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

.product-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    z-index: 3;
    color: white;
}

.badge-new {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.product-overlay {
    position: absolute;
    top: 15px;
    left: 15px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    opacity: 0;
    transition: all 0.3s ease;
    z-index: 2;
}

.product-card:hover .product-overlay {
    opacity: 1;
}

.btn-overlay {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    color: #2c3e50;
    text-decoration: none;
}

.btn-overlay:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: scale(1.1);
    text-decoration: none;
}

.product-info {
    padding: 1.5rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.product-category {
    font-size: 0.9rem;
    color: #a0aec0;
    font-weight: 500;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.product-title {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 1rem;
    line-height: 1.4;
}

.product-link {
    color: #2c3e50;
    text-decoration: none;
    transition: all 0.3s ease;
}

.product-link:hover {
    color: #667eea;
    text-decoration: none;
}

.product-price {
    margin-bottom: 1.5rem;
    margin-top: auto;
}

.current-price {
    font-size: 1.4rem;
    font-weight: 800;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.price-on-request {
    font-size: 1.1rem;
    color: #6c757d;
    font-weight: 600;
}

.product-actions {
    margin-top: auto;
}

.btn-add-cart {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    padding: 12px 24px;
    border-radius: 30px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-add-cart:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    color: white;
    text-decoration: none;
}

.btn-add-cart:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}
</style>