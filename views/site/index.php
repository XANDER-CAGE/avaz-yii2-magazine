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
        ->limit(8)
        ->all();
} catch (Exception $e) {
    $featuredProducts = [];
}

try {
    $newProducts = \app\models\Product::find()
        ->with('category')
        ->where(['>=', 'created_at', date('Y-m-d', strtotime('-30 days'))])
        ->orderBy(['created_at' => SORT_DESC])
        ->limit(4)
        ->all();
} catch (Exception $e) {
    $newProducts = [];
}

try {
    $totalProducts = \app\models\Product::find()->count();
    $totalCategories = \app\models\Category::find()->count();
    $totalOrders = \app\models\Order::find()->count();
} catch (Exception $e) {
    $totalProducts = 0;
    $totalCategories = 0;
    $totalOrders = 0;
}

$this->title = 'Limaron - Современный интернет-магазин';

// Регистрируем мета-теги
$this->registerMetaTag(['name' => 'description', 'content' => 'Современный интернет-магазин Limaron. Широкий выбор качественных товаров с быстрой доставкой по всей России.']);
$this->registerMetaTag(['name' => 'keywords', 'content' => 'интернет-магазин, онлайн покупки, доставка, качественные товары']);
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-background">
        <div class="hero-shape shape-1"></div>
        <div class="hero-shape shape-2"></div>
        <div class="hero-shape shape-3"></div>
    </div>
    
    <div class="container-fluid h-100">
        <div class="row h-100 align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <div class="hero-badge fade-in" data-delay="0">
                        <i class="fas fa-star me-2"></i>
                        Добро пожаловать в Limaron
                    </div>
                    
                    <h1 class="hero-title fade-in" data-delay="200">
                        Стиль и качество<br>
                        <span class="gradient-text">в каждой покупке</span>
                    </h1>
                    
                    <p class="hero-subtitle fade-in" data-delay="400">
                        Откройте для себя коллекцию товаров, которые изменят ваш образ жизни. 
                        От трендовых новинок до проверенной классики — всё для вашего комфорта и стиля.
                    </p>
                    
                    <div class="hero-features fade-in" data-delay="600">
                        <div class="feature-item">
                            <i class="fas fa-shipping-fast"></i>
                            <span>Быстрая доставка</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-shield-alt"></i>
                            <span>Гарантия качества</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-percent"></i>
                            <span>Выгодные цены</span>
                        </div>
                    </div>
                    
                    <div class="hero-actions fade-in" data-delay="800">
                        <?= Html::a(
                            '<i class="fas fa-shopping-bag me-2"></i>Смотреть каталог', 
                            ['/product/index'], 
                            ['class' => 'btn btn-hero-primary btn-lg me-3']
                        ) ?>
                        <?= Html::a(
                            '<i class="far fa-play-circle me-2"></i>О нас', 
                            ['/site/about'], 
                            ['class' => 'btn btn-hero-outline btn-lg']
                        ) ?>
                    </div>
                    
                    <div class="hero-stats fade-in" data-delay="1000">
                        <div class="stat-item">
                            <span class="stat-number" data-count="<?= $totalProducts ?>"><?= $totalProducts ?></span>
                            <span class="stat-label">Товаров</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number" data-count="<?= $totalCategories ?>"><?= $totalCategories ?></span>
                            <span class="stat-label">Категорий</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number" data-count="<?= $totalOrders ?>"><?= $totalOrders ?></span>
                            <span class="stat-label">Заказов</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number" data-count="24">24</span>
                            <span class="stat-label">Часа поддержки</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="hero-visual">
                    <div class="floating-elements">
                        <div class="float-card card-1">
                            <i class="fas fa-truck"></i>
                            <span>Бесплатная доставка</span>
                        </div>
                        <div class="float-card card-2">
                            <i class="fas fa-medal"></i>
                            <span>Премиум качество</span>
                        </div>
                        <div class="float-card card-3">
                            <i class="fas fa-headset"></i>
                            <span>Поддержка 24/7</span>
                        </div>
                    </div>
                    
                    <div class="hero-image">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="scroll-indicator">
        <div class="scroll-arrow"></div>
    </div>
</section>

<!-- Quick Stats Banner -->
<section class="stats-banner">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="stat-card fade-in" data-delay="0">
                    <div class="stat-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <div class="stat-content">
                        <h4>Быстрая доставка</h4>
                        <p>От 1 дня по РФ</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="stat-card fade-in" data-delay="100">
                    <div class="stat-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <div class="stat-content">
                        <h4>Гарантия качества</h4>
                        <p>100% оригинал</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="stat-card fade-in" data-delay="200">
                    <div class="stat-icon">
                        <i class="fas fa-percent"></i>
                    </div>
                    <div class="stat-content">
                        <h4>Выгодные цены</h4>
                        <p>Скидки до 70%</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="stat-card fade-in" data-delay="300">
                    <div class="stat-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div class="stat-content">
                        <h4>Поддержка 24/7</h4>
                        <p>Всегда на связи</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<?php if (!empty($categories)): ?>
<section class="categories-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title fade-in">
                <span class="gradient-text">Популярные категории</span>
            </h2>
            <p class="section-subtitle fade-in" data-delay="200">
                Выберите категорию, которая вас интересует
            </p>
        </div>
        
        <div class="categories-grid">
            <?php foreach ($categories as $index => $category): ?>
                <div class="category-card fade-in" data-delay="<?= $index * 100 ?>">
                    <?= Html::a('', ['/product/index', 'category_id' => $category->id], [
                        'class' => 'category-link'
                    ]) ?>
                    
                    <div class="category-icon">
                        <?php
                        $iconMap = [
                            'электроника' => 'fas fa-laptop',
                            'одежда' => 'fas fa-tshirt', 
                            'дом' => 'fas fa-home',
                            'спорт' => 'fas fa-dumbbell',
                            'красота' => 'fas fa-spa',
                            'игрушки' => 'fas fa-gamepad',
                            'книги' => 'fas fa-book',
                            'автотовары' => 'fas fa-car',
                            'здоровье' => 'fas fa-heartbeat',
                            'техника' => 'fas fa-mobile-alt'
                        ];
                        
                        $iconClass = 'fas fa-tag';
                        $categoryName = mb_strtolower($category->name);
                        foreach ($iconMap as $keyword => $icon) {
                            if (mb_strpos($categoryName, $keyword) !== false) {
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
                                $count = $category->getProducts()->count();
                                echo $count . ' ' . ($count == 1 ? 'товар' : ($count < 5 ? 'товара' : 'товаров'));
                            } catch (Exception $e) {
                                echo 'Товары';
                            }
                            ?>
                        </p>
                    </div>
                    
                    <div class="category-overlay">
                        <span class="overlay-text">
                            <i class="fas fa-arrow-right me-2"></i>
                            Смотреть все
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-5 fade-in" data-delay="800">
            <?= Html::a(
                'Все категории <i class="fas fa-arrow-right ms-2"></i>', 
                ['/product/index'], 
                ['class' => 'btn btn-outline-primary btn-lg']
            ) ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- New Products Section -->
<?php if (!empty($newProducts)): ?>
<section class="new-products-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title fade-in">
                <span class="gradient-text">Новинки</span>
            </h2>
            <p class="section-subtitle fade-in" data-delay="200">
                Последние поступления в наш каталог
            </p>
        </div>
        
        <div class="products-grid">
            <?php foreach ($newProducts as $index => $product): ?>
                <div class="product-card new-product fade-in" data-delay="<?= $index * 150 ?>">
                    <?= $this->render('_product_card', ['product' => $product]) ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Featured Products Section -->
<?php if (!empty($featuredProducts)): ?>
<section class="products-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title fade-in">
                <span class="gradient-text">Рекомендуемые товары</span>
            </h2>
            <p class="section-subtitle fade-in" data-delay="200">
                Специально отобранные для вас товары и хиты продаж
            </p>
        </div>
        
        <div class="products-grid">
            <?php foreach ($featuredProducts as $index => $product): ?>
                <div class="product-card fade-in" data-delay="<?= $index * 100 ?>">
                    <?= $this->render('_product_card', ['product' => $product]) ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-5 fade-in" data-delay="1000">
            <?= Html::a(
                'Смотреть все товары <i class="fas fa-arrow-right ms-2"></i>', 
                ['/product/index'], 
                ['class' => 'btn btn-primary btn-lg px-5']
            ) ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Promo Banner -->
<section class="promo-banner">
    <div class="container">
        <div class="promo-content fade-in">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="promo-text">
                        <h3 class="promo-title">
                            <i class="fas fa-fire me-3"></i>
                            Горячие предложения недели!
                        </h3>
                        <p class="promo-subtitle">
                            Скидки до 50% на популярные товары. Акция действует ограниченное время!
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 text-end">
                    <div class="promo-actions">
                        <?= Html::a(
                            'Смотреть акции <i class="fas fa-fire ms-2"></i>', 
                            ['/product/index', 'sale' => 1], 
                            ['class' => 'btn btn-light btn-lg']
                        ) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title text-white fade-in">Почему выбирают нас?</h2>
            <p class="section-subtitle text-white-50 fade-in" data-delay="200">
                Мы заботимся о каждом клиенте и предоставляем лучший сервис
            </p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card fade-in" data-delay="0">
                <div class="feature-icon">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <h5 class="feature-title">Быстрая доставка</h5>
                <p class="feature-text">
                    Бесплатная доставка от 3000₽. По России за 1-3 дня. 
                    Экспресс-доставка в день заказа по Москве и СПб.
                </p>
            </div>
            
            <div class="feature-card fade-in" data-delay="200">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h5 class="feature-title">Гарантия качества</h5>
                <p class="feature-text">
                    100% оригинальные товары с официальной гарантией. 
                    Возврат и обмен в течение 14 дней.
                </p>
            </div>
            
            <div class="feature-card fade-in" data-delay="400">
                <div class="feature-icon">
                    <i class="fas fa-percent"></i>
                </div>
                <h5 class="feature-title">Выгодные цены</h5>
                <p class="feature-text">
                    Регулярные акции и скидки до 70%. 
                    Бонусная программа для постоянных клиентов.
                </p>
            </div>
            
            <div class="feature-card fade-in" data-delay="600">
                <div class="feature-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <h5 class="feature-title">Удобная оплата</h5>
                <p class="feature-text">
                    Картой, наличными, онлайн или в рассрочку. 
                    Безопасные платежи через защищенное соединение.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section">
    <div class="container">
        <div class="newsletter-content fade-in">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="newsletter-text">
                        <h3 class="newsletter-title">
                            <i class="fas fa-envelope me-3"></i>
                            Подпишитесь на новости
                        </h3>
                        <p class="newsletter-subtitle">
                            Получайте первыми информацию о новинках, акциях и специальных предложениях
                        </p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <form class="newsletter-form" id="newsletter-form">
                        <div class="input-group input-group-lg">
                            <input type="email" class="form-control newsletter-input" 
                                   placeholder="Введите ваш email" required>
                            <button class="btn btn-newsletter" type="submit">
                                <i class="fas fa-paper-plane me-2"></i>
                                Подписаться
                            </button>
                        </div>
                        <small class="text-muted mt-2 d-block">
                            Нажимая кнопку, вы соглашаетесь с условиями обработки персональных данных
                        </small>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Notification Toast Template -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1050">
    <div id="notification-toast" class="toast hide" role="alert">
        <div class="toast-header">
            <i class="fas fa-info-circle me-2 text-primary"></i>
            <strong class="me-auto">Уведомление</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body"></div>
    </div>
</div>

<style>
/* Hero Section Styles */
.hero-section {
    min-height: 100vh;
    position: relative;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    overflow: hidden;
    display: flex;
    align-items: center;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1;
}

.hero-shape {
    position: absolute;
    border-radius: 50%;
    opacity: 0.1;
    animation: float 8s ease-in-out infinite;
}

.hero-shape.shape-1 {
    width: 300px;
    height: 300px;
    background: var(--primary-color);
    top: 10%;
    right: 10%;
}

.hero-shape.shape-2 {
    width: 200px;
    height: 200px;
    background: var(--accent-color);
    bottom: 20%;
    left: 15%;
    animation-delay: 2s;
}

.hero-shape.shape-3 {
    width: 150px;
    height: 150px;
    background: var(--success-color);
    top: 60%;
    right: 30%;
    animation-delay: 4s;
}

.hero-content {
    position: relative;
    z-index: 2;
    padding: 2rem 0;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    background: rgba(102, 126, 234, 0.1);
    color: var(--primary-color);
    padding: 10px 20px;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 1.5rem;
    color: var(--text-primary);
}

.gradient-text {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-subtitle {
    font-size: 1.2rem;
    color: var(--text-secondary);
    margin-bottom: 2rem;
    line-height: 1.6;
}

.hero-features {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    margin-bottom: 2.5rem;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-primary);
}

.feature-item i {
    color: var(--primary-color);
    font-size: 1.1rem;
}

.hero-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 2.5rem;
}

.btn-hero-primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    border: none;
    color: white;
    padding: 15px 30px;
    border-radius: 30px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.btn-hero-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
    color: white;
    text-decoration: none;
}

.btn-hero-outline {
    background: transparent;
    border: 2px solid var(--primary-color);
    color: var(--primary-color);
    padding: 13px 30px;
    border-radius: 30px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
}

.btn-hero-outline:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-3px);
    text-decoration: none;
}

.hero-stats {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 2rem;
    font-weight: 800;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.stat-label {
    display: block;
    font-size: 0.9rem;
    color: var(--text-secondary);
    font-weight: 500;
}

/* Hero Visual */
.hero-visual {
    position: relative;
    height: 500px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.floating-elements {
    position: absolute;
    width: 100%;
    height: 100%;
}

.float-card {
    position: absolute;
    background: white;
    padding: 15px 20px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    animation: float 6s ease-in-out infinite;
}

.float-card i {
    color: var(--primary-color);
    font-size: 1.2rem;
}

.card-1 {
    top: 20%;
    left: 10%;
    animation-delay: 0s;
}

.card-2 {
    top: 60%;
    right: 15%;
    animation-delay: 2s;
}

.card-3 {
    bottom: 25%;
    left: 20%;
    animation-delay: 4s;
}

.hero-image {
    width: 200px;
    height: 200px;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: white;
    animation: pulse 2s ease-in-out infinite;
    box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
}

.scroll-indicator {
    position: absolute;
    bottom: 2rem;
    left: 50%;
    transform: translateX(-50%);
    z-index: 3;
}

.scroll-arrow {
    width: 24px;
    height: 24px;
    border-right: 2px solid var(--primary-color);
    border-bottom: 2px solid var(--primary-color);
    transform: rotate(45deg);
    animation: bounce 2s infinite;
}

/* Stats Banner */
.stats-banner {
    padding: 3rem 0;
    background: white;
    border-bottom: 1px solid #f1f5f9;
}

.stat-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 15px;
    transition: all 0.3s ease;
    height: 100%;
    border: 1px solid transparent;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    border-color: var(--primary-color);
}

.stat-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.stat-content h4 {
    margin: 0 0 0.5rem 0;
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-primary);
}

.stat-content p {
    margin: 0;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

/* Categories Section */
.categories-section {
    padding: 5rem 0;
    background: var(--bg-primary);
}

.section-header {
    margin-bottom: 4rem;
    text-align: center;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 1rem;
    color: var(--text-primary);
}

.section-subtitle {
    font-size: 1.1rem;
    color: var(--text-secondary);
    max-width: 600px;
    margin: 0 auto;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.category-card {
    display: block;
    background: white;
    border-radius: 20px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border: 1px solid #f1f5f9;
    position: relative;
    overflow: hidden;
    text-decoration: none;
    color: inherit;
    height: 100%;
}

.category-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    color: inherit;
    text-decoration: none;
}

.category-link {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 2;
}

.category-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2rem;
    color: white;
    transition: all 0.3s ease;
}

.category-card:hover .category-icon {
    transform: scale(1.1) rotate(10deg);
}

.category-title {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
}

.category-count {
    color: var(--text-secondary);
    font-weight: 500;
    margin-bottom: 1rem;
}

.category-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: white;
    padding: 1rem;
    transform: translateY(100%);
    transition: all 0.3s ease;
    z-index: 1;
}

.category-card:hover .category-overlay {
    transform: translateY(0);
}

.overlay-text {
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* New Products Section */
.new-products-section {
    padding: 4rem 0;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

.new-product {
    position: relative;
}

.new-product::before {
    content: 'NEW';
    position: absolute;
    top: 15px;
    left: 15px;
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    z-index: 3;
}

/* Products Section */
.products-section {
    padding: 5rem 0;
    background: white;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

/* Promo Banner */
.promo-banner {
    padding: 3rem 0;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: white;
}

.promo-content {
    padding: 2rem;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.promo-title {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
}

.promo-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin: 0;
}

.btn-light {
    background: white;
    color: var(--text-primary);
    border: none;
    padding: 12px 24px;
    border-radius: 30px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-light:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(255, 255, 255, 0.3);
    color: var(--text-primary);
}

/* Features Section */
.features-section {
    padding: 5rem 0;
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    color: white;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.feature-card {
    text-align: center;
    padding: 2rem;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
    height: 100%;
}

.feature-card:hover {
    transform: translateY(-10px);
    background: rgba(255, 255, 255, 0.15);
}

.feature-icon {
    font-size: 3rem;
    margin-bottom: 1.5rem;
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.feature-title {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.feature-text {
    color: rgba(255, 255, 255, 0.8);
    line-height: 1.6;
}

/* Newsletter Section */
.newsletter-section {
    padding: 4rem 0;
    background: white;
}

.newsletter-content {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-radius: 20px;
    padding: 3rem;
    border: 1px solid #f1f5f9;
}

.newsletter-title {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
    display: flex;
    align-items: center;
}

.newsletter-subtitle {
    color: var(--text-secondary);
    margin: 0 0 2rem 0;
    font-size: 1.1rem;
}

.newsletter-input {
    border: 2px solid #e2e8f0;
    border-radius: 50px 0 0 50px;
    padding: 15px 20px;
    font-size: 1rem;
    background: white;
}

.newsletter-input:focus {
    border-color: var(--primary-color);
    box-shadow: none;
}

.btn-newsletter {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: white;
    border: none;
    border-radius: 0 50px 50px 0;
    padding: 15px 25px;
    transition: all 0.3s ease;
    font-weight: 600;
}

.btn-newsletter:hover {
    transform: scale(1.05);
    color: white;
}

/* Animations */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: rotate(45deg) translateY(0); }
    40% { transform: rotate(45deg) translateY(-10px); }
    60% { transform: rotate(45deg) translateY(-5px); }
}

.fade-in {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s ease;
}

.fade-in.visible {
    opacity: 1;
    transform: translateY(0);
}

/* Responsive Design */
@media (max-width: 992px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .hero-features {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .hero-stats {
        justify-content: center;
        text-align: center;
    }
    
    .hero-actions {
        justify-content: center;
        text-align: center;
    }
    
    .float-card {
        display: none;
    }
    
    .categories-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }
    
    .products-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    }
    
    .features-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }
    
    .promo-content {
        text-align: center;
    }
    
    .promo-actions {
        text-align: center !important;
        margin-top: 1rem;
    }
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
        text-align: center;
    }
    
    .hero-subtitle {
        text-align: center;
    }
    
    .hero-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .hero-stats {
        flex-direction: column;
        gap: 1rem;
    }
    
    .stat-card {
        flex-direction: column;
        text-align: center;
    }
    
    .newsletter-content {
        padding: 2rem 1rem;
    }
    
    .newsletter-title {
        font-size: 1.5rem;
    }
    
    .categories-grid {
        grid-template-columns: 1fr;
    }
    
    .products-grid {
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    }
    
    .features-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 576px) {
    .hero-title {
        font-size: 1.8rem;
    }
    
    .section-title {
        font-size: 1.8rem;
    }
    
    .btn-hero-primary,
    .btn-hero-outline {
        padding: 12px 20px;
        font-size: 0.9rem;
    }
    
    .stat-number {
        font-size: 1.5rem;
    }
    
    .promo-title {
        font-size: 1.5rem;
        flex-direction: column;
        text-align: center;
    }
    
    .promo-title i {
        margin-bottom: 0.5rem;
    }
}
</style>

<?php
// JavaScript для интерактивности
$js = <<<JS
document.addEventListener('DOMContentLoaded', function() {
    initHomePage();
});

function initHomePage() {
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
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Применяем наблюдатель ко всем элементам с классом fade-in
    document.querySelectorAll('.fade-in').forEach(el => {
        observer.observe(el);
    });

    // Анимация счетчиков
    const counters = document.querySelectorAll('[data-count]');
    const animateCounters = () => {
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-count'));
            const increment = target / 100;
            let current = 0;
            
            const updateCounter = () => {
                if (current < target) {
                    counter.textContent = Math.ceil(current);
                    current += increment;
                    setTimeout(updateCounter, 30);
                } else {
                    counter.textContent = target;
                }
            };
            
            updateCounter();
        });
    };

    // Запуск анимации счетчиков при скролле до hero-stats
    const heroStats = document.querySelector('.hero-stats');
    if (heroStats) {
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounters();
                    statsObserver.unobserve(entry.target);
                }
            });
        });
        statsObserver.observe(heroStats);
    }

    // AJAX для добавления в корзину
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const originalText = this.innerHTML;
            const productId = this.dataset.id;
            
            // Показываем состояние загрузки
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Добавляем...';
            this.disabled = true;

            // Отправляем AJAX запрос
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
                    
                    // Показываем уведомление
                    showNotification(data.message || 'Товар добавлен в корзину', 'success');
                    
                    // Обновляем счетчик корзины
                    updateCartBadge(data.totalCount);
                    
                    // Возвращаем кнопку в исходное состояние через 3 секунды
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.classList.remove('btn-success');
                        this.classList.add('btn-add-cart');
                        this.disabled = false;
                    }, 3000);
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
            
            // Простая валидация email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showNotification('Пожалуйста, введите корректный email адрес', 'error');
                return;
            }
            
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Подписываем...';
            btn.disabled = true;
            
            // Имитация отправки (здесь должен быть реальный AJAX запрос)
            setTimeout(() => {
                showNotification('Спасибо за подписку! Вы будете получать наши новости.', 'success');
                btn.innerHTML = originalText;
                btn.disabled = false;
                this.reset();
            }, 1500);
        });
    }

    // Плавный скролл для якорных ссылок
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Функция показа уведомлений
function showNotification(message, type = 'info') {
    const toast = document.getElementById('notification-toast');
    const toastBody = toast.querySelector('.toast-body');
    const toastHeader = toast.querySelector('.toast-header');
    
    // Устанавливаем иконку в зависимости от типа
    const iconMap = {
        success: 'fas fa-check-circle text-success',
        error: 'fas fa-exclamation-circle text-danger',
        warning: 'fas fa-exclamation-triangle text-warning',
        info: 'fas fa-info-circle text-primary'
    };
    
    const icon = toastHeader.querySelector('i');
    icon.className = iconMap[type] + ' me-2';
    
    toastBody.textContent = message;
    
    // Показываем toast
    const bsToast = new bootstrap.Toast(toast, {
        autohide: true,
        delay: 5000
    });
    bsToast.show();
}

// Функция обновления счетчика корзины
function updateCartBadge(count) {
    const badges = document.querySelectorAll('.cart-count, .action-badge');
    badges.forEach(badge => {
        badge.textContent = count;
        if (count > 0) {
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }
    });
}

// Обработка ошибок JavaScript
window.addEventListener('error', function(e) {
    console.error('JavaScript Error:', e.error);
});
JS;

$this->registerJs($js);
?>