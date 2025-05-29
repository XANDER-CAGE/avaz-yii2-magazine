<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Category;
use app\models\Product;

// Категории и товары
$categories = Category::find()
    ->select(['category.*', 'product_count' => 'COUNT(p.id)'])
    ->joinWith('products p')
    ->groupBy('category.id')
    ->orderBy(['product_count' => SORT_DESC])
    ->limit(8)
    ->all();
$featuredProducts = Product::find()->limit(6)->all();

$this->title = 'Limaron - Современный интернет-магазин';
?>

<!-- Hero Section -->
<section class="hero-modern" id="home">
    <div class="hero-background"></div>
    <div class="hero-particles"></div>
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6">
                <div class="hero-content">
                    <div class="hero-badge">
                        <i class="fas fa-star"></i>
                        <span>Рейтинг №1 в России</span>
                    </div>
                    <h1 class="hero-title">
                        Добро пожаловать в 
                        <span class="gradient-text">будущее покупок</span>
                    </h1>
                    <p class="hero-description">
                        Откройте для себя уникальную коллекцию товаров с невероятными скидками, 
                        быстрой доставкой и гарантией качества
                    </p>
                    <div class="hero-actions">
                        <?= Html::a(
                            '<i class="fas fa-shopping-bag me-2"></i>Начать покупки',
                            ['/product/index'],
                            ['class' => 'btn-hero-primary']
                        ) ?>
                        <?= Html::a(
                            '<i class="fas fa-play me-2"></i>Смотреть видео',
                            '#',
                            ['class' => 'btn-hero-secondary', 'data-video' => 'true']
                        ) ?>
                    </div>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number"><?= Product::find()->count() ?>+</span>
                            <span class="stat-label">товаров</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">50K+</span>
                            <span class="stat-label">клиентов</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">99%</span>
                            <span class="stat-label">довольных</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-visual">
                    <div class="floating-card card-1">
                        <i class="fas fa-bolt"></i>
                        <span>Быстрая доставка</span>
                    </div>
                    <div class="floating-card card-2">
                        <i class="fas fa-shield-alt"></i>
                        <span>Гарантия качества</span>
                    </div>
                    <div class="floating-card card-3">
                        <i class="fas fa-tags"></i>
                        <span>Лучшие цены</span>
                    </div>
                    <div class="hero-main-graphic">
                        <div class="graphic-circle circle-1"></div>
                        <div class="graphic-circle circle-2"></div>
                        <div class="graphic-circle circle-3"></div>
                        <div class="shopping-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-modern py-5" id="categories">
    <div class="container">
        <div class="section-header text-center mb-5">
            <span class="section-badge">Популярные категории</span>
            <h2 class="section-title">
                Найдите то, что <span class="gradient-text">вам нужно</span>
            </h2>
            <p class="section-description">
                Широкий выбор качественных товаров в различных категориях
            </p>
        </div>

        <div class="row g-4">
            <?php foreach ($categories as $index => $category): ?>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                    <?= Html::a('', ['/product/index', 'category_id' => $category->id], [
                        'class' => 'category-card-modern',
                        'data-category' => $category->id
                    ]) ?>
                        <div class="category-icon">
                            <?php
                            // Простая логика для иконок категорий
                            $icons = [
                                'fas fa-laptop' => ['электроника', 'компьютер', 'гаджет'],
                                'fas fa-tshirt' => ['одежда', 'мода', 'стиль'],
                                'fas fa-home' => ['дом', 'интерьер', 'мебель'],
                                'fas fa-gamepad' => ['игры', 'развлечение'],
                                'fas fa-book' => ['книги', 'образование'],
                                'fas fa-dumbbell' => ['спорт', 'фитнес'],
                                'fas fa-car' => ['авто', 'транспорт'],
                                'fas fa-gift' => ['подарки', 'сувенир']
                            ];
                            
                            $categoryIcon = 'fas fa-box';
                            foreach ($icons as $icon => $keywords) {
                                foreach ($keywords as $keyword) {
                                    if (stripos($category->name, $keyword) !== false) {
                                        $categoryIcon = $icon;
                                        break 2;
                                    }
                                }
                            }
                            ?>
                            <i class="<?= $categoryIcon ?>"></i>
                        </div>
                        <div class="category-content">
                            <h4 class="category-title"><?= Html::encode($category->name) ?></h4>
                            <p class="category-count">
                                <?= $category->getProducts()->count() ?>+ товаров
                            </p>
                            <div class="category-arrow">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                        <div class="category-hover-effect"></div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-5">
            <?= Html::a(
                'Смотреть все категории <i class="fas fa-arrow-right ms-2"></i>',
                ['/product/index'],
                ['class' => 'btn-modern-outline']
            ) ?>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="products-modern py-5" id="products">
    <div class="container">
        <div class="section-header text-center mb-5">
            <span class="section-badge">Рекомендуем</span>
            <h2 class="section-title">
                <span class="gradient-text">Популярные</span> товары
            </h2>
            <p class="section-description">
                Самые покупаемые и высоко оцененные товары наших клиентов
            </p>
        </div>

        <div class="row g-4">
            <?php foreach ($featuredProducts as $index => $product): ?>
                <div class="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="<?= $index * 150 ?>">
                    <div class="product-card-modern" data-product="<?= $product->id ?>">
                        <div class="product-image-wrapper">
                            <img src="<?= $product->image ?: '/img/no-image.jpg' ?>" 
                                 alt="<?= Html::encode($product->name) ?>" 
                                 class="product-image">
                            
                            <?php if (strtotime($product->created_at) > strtotime('-30 days')): ?>
                                <div class="product-badge new">Новинка</div>
                            <?php endif; ?>
                            
                            <div class="product-overlay">
                                <button class="btn-quick-view" data-id="<?= $product->id ?>">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-wishlist" data-id="<?= $product->id ?>">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="product-content">
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
                            
                            <div class="product-rating">
                                <div class="stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?= $i <= 4 ? 'active' : '' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <span class="rating-text">(4.0)</span>
                            </div>
                            
                            <div class="product-price">
                                <?php if ($product->price): ?>
                                    <span class="current-price">
                                        <?= Yii::$app->formatter->asCurrency($product->price, 'RUB') ?>
                                    </span>
                                <?php else: ?>
                                    <span class="price-on-request">Цена по запросу</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="product-actions">
                                <?= Html::a(
                                    '<i class="fas fa-shopping-cart me-2"></i>В корзину',
                                    ['/cart/add', 'id' => $product->id],
                                    [
                                        'class' => 'btn-add-to-cart',
                                        'data-id' => $product->id,
                                        'data-name' => $product->name
                                    ]
                                ) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-5">
            <?= Html::a(
                'Смотреть все товары <i class="fas fa-arrow-right ms-2"></i>',
                ['/product/index'],
                ['class' => 'btn-modern-primary btn-lg']
            ) ?>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-modern py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="0">
                <div class="feature-card-modern">
                    <div class="feature-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h5 class="feature-title">Быстрая доставка</h5>
                    <p class="feature-description">Доставляем по всей России за 1-3 дня</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card-modern">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5 class="feature-title">Гарантия качества</h5>
                    <p class="feature-description">100% оригинальные товары с гарантией</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card-modern">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h5 class="feature-title">24/7 поддержка</h5>
                    <p class="feature-description">Круглосуточная помощь наших специалистов</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card-modern">
                    <div class="feature-icon">
                        <i class="fas fa-undo"></i>
                    </div>
                    <h5 class="feature-title">Легкий возврат</h5>
                    <p class="feature-description">Возврат товара в течение 30 дней</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-modern py-5">
    <div class="container">
        <div class="newsletter-card">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="newsletter-content">
                        <h3 class="newsletter-title">
                            Получайте <span class="gradient-text">эксклюзивные</span> предложения
                        </h3>
                        <p class="newsletter-description">
                            Подпишитесь на рассылку и будьте первыми в курсе скидок и новинок
                        </p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <form class="newsletter-form" id="newsletter-form">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Введите ваш email" required>
                            <button class="btn-newsletter" type="submit">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// JavaScript для AJAX функций корзины
$js = <<<JS
$(document).ready(function() {
    // Ajax добавление товара в корзину
    $('.btn-add-to-cart').on('click', function(e) {
        e.preventDefault();
        var btn = $(this);
        var productId = btn.data('id');
        var productName = btn.data('name');
        var originalText = btn.html();

        // Анимация загрузки
        btn.html('<div class="loading-spinner"></div>Добавляем...');
        btn.prop('disabled', true);

        $.ajax({
            url: btn.attr('href'),
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Успешное добавление
                    btn.html('<i class="fas fa-check me-2"></i>Добавлено!');
                    btn.addClass('success-state');
                    
                    // Обновляем счетчик корзины
                    if (response.totalCount) {
                        $('.cart-count').text(response.totalCount);
                        $('.cart-count').addClass('bounce-animation');
                    }
                    
                    // Показываем уведомление
                    showNotification('success', productName + ' добавлен в корзину!');
                    
                    // Возвращаем кнопку в исходное состояние через 2 секунды
                    setTimeout(function() {
                        btn.html(originalText);
                        btn.removeClass('success-state');
                        btn.prop('disabled', false);
                    }, 2000);
                } else {
                    showNotification('error', 'Ошибка при добавлении товара');
                    btn.html(originalText);
                    btn.prop('disabled', false);
                }
            },
            error: function() {
                showNotification('error', 'Ошибка при обращении к серверу');
                btn.html(originalText);
                btn.prop('disabled', false);
            }
        });
    });
    
    // Быстрый просмотр товара
    $('.btn-quick-view').on('click', function(e) {
        e.preventDefault();
        var productId = $(this).data('id');
        // Здесь можно добавить модальное окно с быстрым просмотром
        console.log('Quick view for product:', productId);
    });
    
    // Wishlist функция
    $('.btn-wishlist').on('click', function(e) {
        e.preventDefault();
        var btn = $(this);
        var productId = btn.data('id');
        
        // Переключаем иконку
        var icon = btn.find('i');
        if (icon.hasClass('far')) {
            icon.removeClass('far').addClass('fas');
            btn.addClass('active');
            showNotification('success', 'Товар добавлен в избранное');
        } else {
            icon.removeClass('fas').addClass('far');
            btn.removeClass('active');
            showNotification('info', 'Товар удален из избранного');
        }
    });
    
    // Подписка на рассылку
    $('#newsletter-form').on('submit', function(e) {
        e.preventDefault();
        var email = $(this).find('input[type="email"]').val();
        
        // Здесь должна быть AJAX отправка формы
        showNotification('success', 'Спасибо за подписку!');
        $(this)[0].reset();
    });
});

// Функция показа уведомлений
function showNotification(type, message) {
    var alertClass = type === 'success' ? 'alert-success' : 
                    type === 'error' ? 'alert-danger' : 'alert-info';
    
    var notification = $('<div class="alert ' + alertClass + ' alert-dismissible fade show notification-toast" role="alert">')
        .html(message + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>')
        .appendTo('body');

    // Автоматическое скрытие через 4 секунды
    setTimeout(function() {
        notification.alert('close');
    }, 4000);
}
JS;

$this->registerJs($js);
?>