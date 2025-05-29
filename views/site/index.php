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
<section class="hero-section hero-interactive">
    <div class="container-fluid">
        <div class="row g-0">
            <div class="col-lg-5">
                <div class="hero-content">
                    <div class="content-wrapper">
                        <span class="hero-label">Добро пожаловать в Limaron</span>
                        <h1 class="hero-title">
                            Стиль и качество<br>
                            <span class="gradient-text">в каждой покупке</span>
                        </h1>
                        <p class="hero-description">
                            Откройте для себя коллекцию товаров, которые изменят ваш образ жизни. 
                            От трендовых новинок до проверенной классики.
                        </p>
                        
                        <div class="hero-benefits">
                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <div class="benefit-text">
                                    <strong>Быстрая доставка</strong>
                                    <small>От 1 дня по России</small>
                                </div>
                            </div>
                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <i class="fas fa-medal"></i>
                                </div>
                                <div class="benefit-text">
                                    <strong>Премиум качество</strong>
                                    <small>Только лучшие бренды</small>
                                </div>
                            </div>
                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <i class="fas fa-headset"></i>
                                </div>
                                <div class="benefit-text">
                                    <strong>Поддержка 24/7</strong>
                                    <small>Всегда готовы помочь</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="hero-actions">
                            <?= Html::a(
                                'Смотреть каталог', 
                                ['/product/index'], 
                                ['class' => 'btn btn-hero-primary btn-lg']
                            ) ?>
                            <div class="hero-social">
                                <span>Следите за нами:</span>
                                <div class="social-links">
                                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                                    <a href="#" class="social-link"><i class="fab fa-telegram"></i></a>
                                    <a href="#" class="social-link"><i class="fab fa-vk"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="hero-showcase">
                    <?php if (!empty($featuredProducts)): ?>
                        <div class="product-carousel">
                            <?php foreach (array_slice($featuredProducts, 0, 3) as $index => $product): ?>
                                <div class="showcase-item item-<?= $index + 1 ?>">
                                    <div class="product-preview">
                                        <?= Html::img(
                                            $product->image ?: '/img/no-image.jpg', 
                                            ['alt' => Html::encode($product->name), 'class' => 'preview-image']
                                        ) ?>
                                        <div class="product-info">
                                            <h5><?= Html::encode($product->name) ?></h5>
                                            <div class="price">
                                                <?= Yii::$app->formatter->asCurrency($product->price, 'RUB') ?>
                                            </div>
                                            <?= Html::a(
                                                'Купить', 
                                                ['/cart/add', 'id' => $product->id], 
                                                ['class' => 'btn btn-sm btn-light add-to-cart', 'data-id' => $product->id]
                                            ) ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="showcase-decorations">
                        <div class="decoration dec-1"></div>
                        <div class="decoration dec-2"></div>
                        <div class="decoration dec-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Общие стили для всех вариантов */
.hero-section {
    min-height: 100vh;
    position: relative;
    overflow: hidden;
}

/* ВАРИАНТ 1: Минималистичный */
.hero-minimal {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

.hero-minimal .hero-badge {
    display: inline-flex;
    align-items: center;
    background: rgba(102, 126, 234, 0.1);
    color: var(--primary);
    padding: 8px 16px;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    animation: fadeInUp 0.8s ease-out;
}

.hero-minimal .hero-title {
    font-size: 3.5rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 1.5rem;
    color: #2c3e50;
    animation: fadeInUp 0.8s ease-out 0.2s both;
}

.hero-minimal .text-highlight {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-minimal .hero-subtitle {
    font-size: 1.2rem;
    color: #6c757d;
    margin-bottom: 2rem;
    line-height: 1.6;
    animation: fadeInUp 0.8s ease-out 0.4s both;
}

.hero-features {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    margin-bottom: 2.5rem;
    animation: fadeInUp 0.8s ease-out 0.6s both;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    color: #495057;
}

.feature-item i {
    color: var(--primary);
    font-size: 1.1rem;
}

.hero-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    animation: fadeInUp 0.8s ease-out 0.8s both;
}

.btn-hero-primary {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
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
    border: 2px solid var(--primary);
    color: var(--primary);
    padding: 13px 30px;
    border-radius: 30px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
}

.btn-hero-outline:hover {
    background: var(--primary);
    color: white;
    transform: translateY(-3px);
    text-decoration: none;
}

/* Floating элементы */
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
    color: var(--primary);
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
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: white;
    animation: pulse 2s ease-in-out infinite;
}

/* Фоновые фигуры */
.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: -1;
}

.bg-shape {
    position: absolute;
    border-radius: 50%;
    opacity: 0.1;
}

.shape-1 {
    width: 300px;
    height: 300px;
    background: var(--primary);
    top: 10%;
    right: 10%;
    animation: float 8s ease-in-out infinite;
}

.shape-2 {
    width: 200px;
    height: 200px;
    background: var(--accent);
    bottom: 20%;
    left: 15%;
    animation: float 10s ease-in-out infinite reverse;
}

.shape-3 {
    width: 150px;
    height: 150px;
    background: var(--success);
    top: 60%;
    right: 30%;
    animation: float 12s ease-in-out infinite;
}

/* ВАРИАНТ 2: Gradient Hero */
.hero-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    color: white;
    text-align: center;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.3);
    z-index: 1;
}

.hero-gradient .container {
    position: relative;
    z-index: 2;
}

.hero-gradient .hero-title {
    font-size: 4rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
}

.typed-text {
    border-right: 2px solid white;
    animation: blink 1s infinite;
}

.hero-gradient .hero-subtitle {
    font-size: 1.3rem;
    opacity: 0.9;
    margin-bottom: 3rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.hero-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.stat-card {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    padding: 2rem 1rem;
    border-radius: 15px;
    border: 1px solid rgba(255,255,255,0.2);
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 800;
    display: block;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.8;
}

.hero-search {
    margin-bottom: 3rem;
}

.search-wrapper {
    position: relative;
    max-width: 500px;
    margin: 0 auto;
}

.search-input {
    width: 100%;
    padding: 20px 60px 20px 25px;
    border: none;
    border-radius: 50px;
    font-size: 1.1rem;
    background: rgba(255,255,255,0.95);
    color: #333;
}

.search-input:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(255,255,255,0.3);
}

.search-btn {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    width: 45px;
    height: 45px;
    border: none;
    border-radius: 50%;
    background: var(--primary);
    color: white;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.search-btn:hover {
    background: var(--secondary);
    transform: translateY(-50%) scale(1.1);
}

.btn-hero-light {
    background: rgba(255,255,255,0.2);
    border: 2px solid rgba(255,255,255,0.3);
    color: white;
    padding: 15px 40px;
    border-radius: 30px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.btn-hero-light:hover {
    background: rgba(255,255,255,0.3);
    color: white;
    text-decoration: none;
    transform: translateY(-3px);
}

/* Scroll индикатор */
.scroll-indicator {
    position: absolute;
    bottom: 2rem;
    left: 50%;
    transform: translateX(-50%);
    z-index: 3;
}

.mouse {
    width: 25px;
    height: 40px;
    border: 2px solid rgba(255,255,255,0.7);
    border-radius: 13px;
    position: relative;
    margin-bottom: 10px;
}

.wheel {
    width: 3px;
    height: 6px;
    background: rgba(255,255,255,0.7);
    border-radius: 2px;
    position: absolute;
    top: 8px;
    left: 50%;
    transform: translateX(-50%);
    animation: scroll 2s infinite;
}

.arrows {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.arrows span {
    width: 8px;
    height: 8px;
    border: 2px solid rgba(255,255,255,0.7);
    border-top: none;
    border-left: none;
    transform: rotate(45deg);
    margin: -2px 0;
    animation: arrow-move 2s infinite;
}

.arrows span:nth-child(2) {
    animation-delay: 0.2s;
}

.arrows span:nth-child(3) {
    animation-delay: 0.4s;
}

/* ВАРИАНТ 3: Интерактивный Hero */
.hero-interactive {
    background: #f8fafc;
    padding: 0;
}

.hero-interactive .hero-content {
    background: white;
    min-height: 100vh;
    display: flex;
    align-items: center;
    padding: 2rem;
}

.content-wrapper {
    max-width: 500px;
}

.hero-label {
    display: inline-block;
    background: rgba(102, 126, 234, 0.1);
    color: var(--primary);
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.hero-interactive .hero-title {
    font-size: 3rem;
    font-weight: 800;
    line-height: 1.2;
    color: #2c3e50;
    margin-bottom: 1.5rem;
}

.gradient-text {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-description {
    font-size: 1.1rem;
    color: #6c757d;
    line-height: 1.6;
    margin-bottom: 2.5rem;
}

.hero-benefits {
    margin-bottom: 2.5rem;
}

.benefit-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.benefit-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.benefit-text strong {
    display: block;
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.benefit-text small {
    color: #6c757d;
    font-size: 0.9rem;
}

.hero-social {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-top: 1.5rem;
}

.hero-social span {
    font-size: 0.9rem;
    color: #6c757d;
}

.social-links {
    display: flex;
    gap: 0.5rem;
}

.social-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    background: #f8fafc;
    color: #6c757d;
    border-radius: 50%;
    text-decoration: none;
    transition: all 0.3s ease;
}

.social-link:hover {
    background: var(--primary);
    color: white;
    transform: translateY(-2px);
    text-decoration: none;
}

/* Showcase товаров */
.hero-showcase {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}

.product-carousel {
    position: relative;
    width: 100%;
    height: 400px;
}

.showcase-item {
    position: absolute;
    background: white;
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    width: 250px;
}

.item-1 {
    top: 50px;
    left: 50px;
    transform: rotate(-5deg);
    z-index: 3;
}

.item-2 {
    top: 100px;
    right: 100px;
    transform: rotate(3deg);
    z-index: 2;
}

.item-3 {
    bottom: 80px;
    left: 50%;
    transform: translateX(-50%) rotate(-2deg);
    z-index: 1;
}

.showcase-item:hover {
    transform: scale(1.05) rotate(0deg) !important;
    z-index: 10 !important;
}

.preview-image {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 10px;
    margin-bottom: 1rem;
}

.product-info h5 {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #2c3e50;
}

.price {
    font-size: 1.2rem;
    font-weight: 800;
    color: var(--primary);
    margin-bottom: 1rem;
}

.showcase-decorations {
    position: absolute;
    width: 100%;
    height: 100%;
    pointer-events: none;
}

.decoration {
    position: absolute;
    border-radius: 50%;
    background: rgba(255,255,255,0.1);
    animation: float 8s ease-in-out infinite;
}

.dec-1 {
    width: 100px;
    height: 100px;
    top: 10%;
    right: 10%;
}

.dec-2 {
    width: 60px;
    height: 60px;
    bottom: 20%;
    left: 20%;
    animation-delay: 2s;
}

.dec-3 {
    width: 80px;
    height: 80px;
    top: 70%;
    right: 30%;
    animation-delay: 4s;
}

/* Анимации */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

@keyframes blink {
    0%, 50% { opacity: 1; }
    51%, 100% { opacity: 0; }
}

@keyframes scroll {
    0% { opacity: 1; top: 8px; }
    100% { opacity: 0; top: 20px; }
}

@keyframes arrow-move {
    0%, 100% { opacity: 0; }
    50% { opacity: 1; }
}

/* Адаптивность */
@media (max-width: 992px) {
    .hero-minimal .hero-title,
    .hero-gradient .hero-title,
    .hero-interactive .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-features {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .hero-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .hero-actions {
        justify-content: center;
        text-align: center;
    }
    
    .hero-interactive .hero-content {
        text-align: center;
    }
    
    .showcase-item {
        position: static !important;
        transform: none !important;
        margin-bottom: 2rem;
        width: 100%;
        max-width: 300px;
        margin: 0 auto 2rem;
    }
    
    .product-carousel {
        height: auto;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
}

@media (max-width: 576px) {
    .hero-minimal .hero-title,
    .hero-gradient .hero-title,
    .hero-interactive .hero-title {
        font-size: 2rem;
    }
    
    .hero-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .hero-stats {
        grid-template-columns: 1fr;
    }
    
    .float-card {
        display: none;
    }
    
    .hero-social {
        justify-content: center;
        flex-direction: column;
        text-align: center;
    }
}
</style>

<?php
// JavaScript для интерактивности
$js = <<<JS
document.addEventListener('DOMContentLoaded', function() {
    // Typing эффект для варианта 2
    const typedTextElement = document.querySelector('.typed-text');
    if (typedTextElement) {
        const words = ['идеальный', 'любимый', 'надёжный', 'современный'];
        let wordIndex = 0;
        let charIndex = 0;
        let isDeleting = false;
        
        function typeWriter() {
            const currentWord = words[wordIndex];
            
            if (isDeleting) {
                typedTextElement.textContent = currentWord.substring(0, charIndex - 1);
                charIndex--;
            } else {
                typedTextElement.textContent = currentWord.substring(0, charIndex + 1);
                charIndex++;
            }
            
            let timeout = isDeleting ? 100 : 150;
            
            if (!isDeleting && charIndex === currentWord.length) {
                timeout = 2000;
                isDeleting = true;
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                wordIndex = (wordIndex + 1) % words.length;
            }
            
            setTimeout(typeWriter, timeout);
        }
        
        typeWriter();
    }
    
    // Счетчики для варианта 2
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
                    counter.textContent = target.toLocaleString();
                }
            };
            
            updateCounter();
        });
    };
    
    // Запуск счетчиков при скролле
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounters();
                observer.unobserve(entry.target);
            }
        });
    });
    
    const statsSection = document.querySelector('.hero-stats');
    if (statsSection) {
        observer.observe(statsSection);
    }
    
    // AJAX для добавления в корзину
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-id');
            const originalText = this.textContent;
            
            this.textContent = 'Добавляем...';
            this.disabled = true;
            
            fetch(this.href, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.textContent = 'Добавлено!';
                    this.classList.add('btn-success');
                    
                    setTimeout(() => {
                        this.textContent = originalText;
                        this.classList.remove('btn-success');
                        this.disabled = false;
                    }, 2000);
                } else {
                    alert(data.message || 'Ошибка при добавлении товара');
                    this.textContent = originalText;
                    this.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.textContent = originalText;
                this.disabled = false;
            });
        });
    });
});
JS;

$this->registerJs($js);
?>

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