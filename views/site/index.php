<?php

use yii\helpers\Html;
use yii\helpers\Url;

// Безопасно получаем данные с проверкой существования моделей
try {
    $categories = \app\models\Category::find()
        ->orderBy(['id' => SORT_ASC])
        ->limit(8)
        ->all();
} catch (Exception $e) {
    $categories = [];
}

try {
    $featuredProducts = \app\models\Product::find()
        ->with('category')
        ->orderBy(['created_at' => SORT_DESC])
        ->limit(6)
        ->all();
} catch (Exception $e) {
    $featuredProducts = [];
}

try {
    $totalProducts = \app\models\Product::find()->count();
    $totalCategories = \app\models\Category::find()->count();
} catch (Exception $e) {
    $totalProducts = 0;
    $totalCategories = 0;
}

$this->title = 'Limaron - Современный интернет-магазин';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-background"></div>
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="hero-title">
                        Добро пожаловать в 
                        <span class="gradient-text">будущее покупок</span>
                    </h1>
                    <p class="hero-subtitle">
                        Откройте для себя уникальную коллекцию товаров с невероятными скидками и быстрой доставкой по всей России
                    </p>
                    <div class="hero-buttons">
                        <?= Html::a(
                            'Начать покупки <i class="fas fa-arrow-right ms-2"></i>', 
                            ['/product/index'], 
                            ['class' => 'btn-hero-primary']
                        ) ?>
                        <?= Html::a(
                            '<i class="fas fa-play me-2"></i> Смотреть видео', 
                            '#', 
                            ['class' => 'btn-hero-secondary ms-3']
                        ) ?>
                    </div>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-number"><?= $totalProducts ?>+</div>
                            <div class="stat-label">Товаров</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?= $totalCategories ?>+</div>
                            <div class="stat-label">Категорий</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">24/7</div>
                            <div class="stat-label">Поддержка</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-visual">
                    <div class="floating-card card-1">
                        <i class="fas fa-shipping-fast"></i>
                        <span>Быстрая доставка</span>
                    </div>
                    <div class="floating-card card-2">
                        <i class="fas fa-shield-alt"></i>
                        <span>Гарантия качества</span>
                    </div>
                    <div class="floating-card card-3">
                        <i class="fas fa-headset"></i>
                        <span>24/7 поддержка</span>
                    </div>
                    <div class="hero-main-icon">
                        <i class="fas fa-store"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="scroll-indicator">
        <div class="scroll-arrow"></div>
    </div>
</section>

<!-- Categories Section -->
<?php if (!empty($categories)): ?>
<section class="categories-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">
                <span class="gradient-text">Популярные категории</span>
            </h2>
            <p class="section-subtitle">Выберите категорию, которая вас интересует</p>
        </div>
        
        <div class="categories-grid">
            <?php foreach ($categories as $index => $category): ?>
                <div class="category-card fade-in" data-delay="<?= $index * 100 ?>">
                    <?= Html::a('', ['/product/index', 'category_id' => $category->id], [
                        'class' => 'category-link'
                    ]) ?>
                    
                    <div class="category-icon">
                        <?php
                        // Иконки для разных категорий
                        $icons = [
                            'электроника' => 'fas fa-laptop',
                            'одежда' => 'fas fa-tshirt', 
                            'дом' => 'fas fa-home',
                            'спорт' => 'fas fa-dumbbell',
                            'красота' => 'fas fa-spa',
                            'игрушки' => 'fas fa-gamepad',
                            'книги' => 'fas fa-book',
                            'автотовары' => 'fas fa-car'
                        ];
                        
                        $iconClass = 'fas fa-tag'; // дефолтная иконка
                        foreach ($icons as $keyword => $icon) {
                            if (stripos($category->name, $keyword) !== false) {
                                $iconClass = $icon;
                                break;
                            }
                        }
                        ?>
                        <i class="<?= $iconClass ?>"></i>
                    </div>
                    
                    <div class="category-content">
                        <h4 class="category-title"><?= Html::encode($category->name) ?></h4>
                        <p class="category-count">
                            <?php
                            try {
                                echo $category->getProducts()->count() . ' товаров';
                            } catch (Exception $e) {
                                echo 'Товары';
                            }
                            ?>
                        </p>
                    </div>
                    
                    <div class="category-overlay">
                        <span class="overlay-text">Смотреть все</span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-5">
            <?= Html::a(
                'Все категории <i class="fas fa-arrow-right ms-2"></i>', 
                ['/product/index'], 
                ['class' => 'btn btn-outline-primary btn-lg']
            ) ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Featured Products Section -->
<?php if (!empty($featuredProducts)): ?>
<section class="products-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">
                <span class="gradient-text">Рекомендуемые товары</span>
            </h2>
            <p class="section-subtitle">Специально отобранные для вас новинки и хиты продаж</p>
        </div>
        
        <div class="products-grid">
            <?php foreach ($featuredProducts as $index => $product): ?>
                <div class="product-card fade-in" data-delay="<?= $index * 150 ?>">
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
                        
                        <div class="product-overlay">
                            <div class="overlay-buttons">
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
                                        'title' => 'В избранное'
                                    ]
                                ) ?>
                            </div>
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
                                <?= Yii::$app->formatter->asCurrency($product->price, 'RUB') ?>
                            <?php else: ?>
                                <span class="price-request">Цена по запросу</span>
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
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-5">
            <?= Html::a(
                'Смотреть все товары <i class="fas fa-arrow-right ms-2"></i>', 
                ['/product/index'], 
                ['class' => 'btn btn-primary btn-lg px-5']
            ) ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title text-white">Почему выбирают нас?</h2>
            <p class="section-subtitle text-white-50">Мы заботимся о каждом клиенте</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <h5 class="feature-title">Быстрая доставка</h5>
                <p class="feature-text">Бесплатная доставка от 3000₽. По России за 1-3 дня</p>
            </div>
            
            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h5 class="feature-title">Гарантия качества</h5>
                <p class="feature-text">100% оригинальные товары с официальной гарантией</p>
            </div>
            
            
            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="fas fa-percent"></i>
                </div>
                <h5 class="feature-title">Выгодные цены</h5>
                <p class="feature-text">Регулярные акции и скидки до 70%</p>
            </div>
            
            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <h5 class="feature-title">Удобная оплата</h5>
                <p class="feature-text">Картой, наличными или в рассрочку</p>
            </div>
        </div>
    </div>
</section>



<?php
// JavaScript для интерактивности
$js = <<<JS
// Проверяем наличие jQuery
if (typeof jQuery !== 'undefined') {
    jQuery(document).ready(function($) {
        initHomePage();
    });
} else {
    // Если jQuery нет, используем vanilla JS
    document.addEventListener('DOMContentLoaded', function() {
        initHomePage();
    });
}

function initHomePage() {
    // Анимация появления элементов при скролле уже в layout
    
    // AJAX добавление товара в корзину
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    addToCartButtons.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.id;
            const originalText = this.innerHTML;

            // Показываем состояние загрузки
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Добавляем...';
            this.disabled = true;

            // Простой fetch запрос
            fetch(this.href, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Показываем успех
                    this.innerHTML = '<i class="fas fa-check me-2"></i>Добавлено!';
                    this.classList.add('btn-success');
                    this.classList.remove('btn-add-cart');
                    
                    // Обновляем счетчик корзины
                    if (data.totalCount) {
                        const cartCounts = document.querySelectorAll('.cart-count');
                        cartCounts.forEach(count => count.textContent = data.totalCount);
                    }
                    
                    // Показываем уведомление
                    showNotification('Товар добавлен в корзину', 'success');
                    
                    // Возвращаем кнопку в исходное состояние через 2 секунды
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.classList.remove('btn-success');
                        this.classList.add('btn-add-cart');
                        this.disabled = false;
                    }, 2000);
                } else {
                    showNotification(data.message || 'Ошибка при добавлении товара', 'error');
                    this.innerHTML = originalText;
                    this.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Ошибка соединения', 'error');
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    });

    // Подписка на новости
    const newsletterForm = document.getElementById('newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            const btn = this.querySelector('button');
            const originalText = btn.innerHTML;
            
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btn.disabled = true;
            
            // Имитация отправки
            setTimeout(function() {
                showNotification('Спасибо за подписку!', 'success');
                btn.innerHTML = originalText;
                btn.disabled = false;
                newsletterForm.reset();
            }, 1500);
        });
    }
}
JS;

$this->registerJs($js);
?>