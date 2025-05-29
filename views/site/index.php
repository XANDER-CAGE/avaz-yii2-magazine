<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Category;
use app\models\Product;

// Получаем данные для главной страницы
$categories = Category::find()
    ->select(['category.*', 'product_count' => 'COUNT(p.id)'])
    ->joinWith('products p')
    ->groupBy('category.id')
    ->orderBy(['product_count' => SORT_DESC])
    ->limit(8)
    ->all();

$featuredProducts = Product::find()
    ->with('category')
    ->orderBy(['created_at' => SORT_DESC])
    ->limit(6)
    ->all();

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
                            ['class' => 'btn btn-hero-primary']
                        ) ?>
                        <?= Html::a(
                            '<i class="fas fa-play me-2"></i> Смотреть видео', 
                            '#', 
                            ['class' => 'btn btn-hero-secondary ms-3']
                        ) ?>
                    </div>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-number"><?= Product::find()->count() ?>+</div>
                            <div class="stat-label">Товаров</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?= Category::find()->count() ?>+</div>
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
                        <i class="fas fa-shopping-bag"></i>
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
<section class="categories-section">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">
                <span class="gradient-text">Популярные категории</span>
            </h2>
            <p class="section-subtitle">Выберите категорию, которая вас интересует</p>
        </div>
        
        <div class="categories-grid">
            <?php if (!empty($categories)): ?>
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
                            <p class="category-count"><?= $category->getProducts()->count() ?> товаров</p>
                        </div>
                        
                        <div class="category-overlay">
                            <span class="overlay-text">Смотреть все</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">Категории не найдены</p>
                </div>
            <?php endif; ?>
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

<!-- Featured Products Section -->
<section class="products-section">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">
                <span class="gradient-text">Рекомендуемые товары</span>
            </h2>
            <p class="section-subtitle">Специально отобранные для вас новинки и хиты продаж</p>
        </div>
        
        <div class="products-grid">
            <?php if (!empty($featuredProducts)): ?>
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
                            
                            <?php if (strtotime($product->created_at) > strtotime('-30 days')): ?>
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
                            <?php if ($product->category): ?>
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
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">Товары не найдены</p>
                </div>
            <?php endif; ?>
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

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="section-header text-center">
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
                    <i class="fas fa-headset"></i>
                </div>
                <h5 class="feature-title">24/7 поддержка</h5>
                <p class="feature-text">Круглосуточная помощь наших опытных консультантов</p>
            </div>
            
            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="fas fa-undo-alt"></i>
                </div>
                <h5 class="feature-title">Легкий возврат</h5>
                <p class="feature-text">Возврат и обмен товара в течение 30 дней</p>
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

<!-- Newsletter Section -->
<section class="newsletter-section">
    <div class="container">
        <div class="newsletter-content">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h3 class="newsletter-title">Подпишитесь на новости</h3>
                    <p class="newsletter-text">Получайте информацию о новинках и специальных предложениях</p>
                </div>
                <div class="col-lg-6">
                    <form class="newsletter-form" id="newsletter-form">
                        <div class="input-group">
                            <input type="email" class="form-control newsletter-input" placeholder="Ваш email адрес" required>
                            <button class="btn btn-newsletter" type="submit">
                                <i class="fas fa-paper-plane"></i>
                                Подписаться
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// JavaScript для интерактивности
$js = <<<JS
$(document).ready(function() {
    // Анимация появления элементов при скролле
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const delay = entry.target.dataset.delay || 0;
                setTimeout(() => {
                    entry.target.classList.add('visible');
                }, delay);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.fade-in').forEach(el => {
        observer.observe(el);
    });

    // AJAX добавление товара в корзину
    $('.add-to-cart').on('click', function(e) {
        e.preventDefault();
        var btn = $(this);
        var productId = btn.data('id');
        var originalText = btn.html();

        // Показываем состояние загрузки
        btn.html('<i class="fas fa-spinner fa-spin me-2"></i>Добавляем...');
        btn.prop('disabled', true);

        $.ajax({
            url: btn.attr('href'),
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Показываем успех
                    btn.html('<i class="fas fa-check me-2"></i>Добавлено!');
                    btn.addClass('btn-success').removeClass('btn-add-cart');
                    
                    // Обновляем счетчик корзины
                    if (response.totalCount) {
                        $('.cart-count').text(response.totalCount);
                    }
                    
                    // Показываем уведомление
                    showNotification('Товар добавлен в корзину', 'success');
                    
                    // Возвращаем кнопку в исходное состояние через 2 секунды
                    setTimeout(function() {
                        btn.html(originalText);
                        btn.removeClass('btn-success').addClass('btn-add-cart');
                        btn.prop('disabled', false);
                    }, 2000);
                } else {
                    showNotification('Ошибка при добавлении товара', 'error');
                    btn.html(originalText);
                    btn.prop('disabled', false);
                }
            },
            error: function() {
                showNotification('Ошибка соединения', 'error');
                btn.html(originalText);
                btn.prop('disabled', false);
            }
        });
    });

    // Подписка на новости
    $('#newsletter-form').on('submit', function(e) {
        e.preventDefault();
        var email = $(this).find('input[type="email"]').val();
        var btn = $(this).find('button');
        var originalText = btn.html();
        
        btn.html('<i class="fas fa-spinner fa-spin"></i>');
        btn.prop('disabled', true);
        
        // Имитация отправки
        setTimeout(function() {
            showNotification('Спасибо за подписку!', 'success');
            btn.html(originalText);
            btn.prop('disabled', false);
            $('#newsletter-form')[0].reset();
        }, 1500);
    });

    // Функция показа уведомлений
    function showNotification(message, type) {
        var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        var notification = $('<div class="alert ' + alertClass + ' alert-dismissible fade show notification-toast" role="alert">')
            .html(message + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>')
            .css({
                'position': 'fixed',
                'top': '20px',
                'right': '20px',
                'z-index': '9999',
                'min-width': '300px'
            });
        
        $('body').append(notification);
        
        setTimeout(function() {
            notification.alert('close');
        }, 5000);
    }
});
JS;

$this->registerJs($js);
?>