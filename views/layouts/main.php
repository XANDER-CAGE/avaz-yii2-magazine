<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    
    <!-- Preload для важных ресурсов -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Дополнительные мета теги -->
    <meta name="description" content="Limaron - современный интернет-магазин с широким ассортиментом товаров">
    <meta name="keywords" content="интернет-магазин, товары, покупки, доставка">
    <meta name="author" content="Limaron">
    
    <!-- Open Graph для социальных сетей -->
    <meta property="og:title" content="<?= Html::encode($this->title) ?>">
    <meta property="og:description" content="Современный интернет-магазин Limaron">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= Url::to('', true) ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<!-- Прелоадер -->
<div id="preloader">
    <div class="preloader-content">
        <div class="preloader-logo">
            <span class="logo-text">Limaron</span>
        </div>
        <div class="preloader-spinner">
            <div class="spinner-ring"></div>
            <div class="spinner-ring"></div>
            <div class="spinner-ring"></div>
        </div>
    </div>
</div>

<!-- Топ бар -->
<div class="top-bar d-none d-lg-block">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="top-bar-left">
                    <span class="top-bar-item">
                        <i class="fas fa-map-marker-alt"></i>
                        Москва
                    </span>
                    <span class="top-bar-item">
                        <i class="fas fa-phone"></i>
                        8 (800) 555-35-35
                    </span>
                    <span class="top-bar-item">
                        <i class="fas fa-envelope"></i>
                        info@limaron.ru
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="top-bar-right">
                    <?= Html::a('<i class="fas fa-truck me-1"></i>Доставка', ['/site/delivery'], ['class' => 'top-bar-link']) ?>
                    <?= Html::a('<i class="fas fa-credit-card me-1"></i>Оплата', ['/site/payment'], ['class' => 'top-bar-link']) ?>
                    <?= Html::a('<i class="fas fa-question-circle me-1"></i>Помощь', ['/site/help'], ['class' => 'top-bar-link']) ?>
                    
                    <!-- Переключатель темы -->
                    <button class="theme-toggle" id="theme-toggle" title="Переключить тему">
                        <i class="fas fa-moon"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Главная навигация -->
<nav class="navbar navbar-expand-lg main-navbar sticky-top" id="main-navbar">
    <div class="container">
        <!-- Логотип -->
        <a class="navbar-brand" href="<?= Url::home() ?>">
            <div class="brand-logo">
                <span class="brand-icon">
                    <i class="fas fa-shopping-bag"></i>
                </span>
                <span class="brand-text">Limaron</span>
            </div>
        </a>

        <!-- Мобильное меню кнопка -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main-navigation">
            <span class="navbar-toggler-line"></span>
            <span class="navbar-toggler-line"></span>
            <span class="navbar-toggler-line"></span>
        </button>

        <div class="collapse navbar-collapse" id="main-navigation">
            <!-- Основное меню -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <?= Html::a('Главная', ['/site/index'], [
                        'class' => 'nav-link' . (Yii::$app->controller->id === 'site' && Yii::$app->controller->action->id === 'index' ? ' active' : '')
                    ]) ?>
                </li>
                
                <!-- Каталог с мега-меню -->
                <li class="nav-item dropdown mega-dropdown">
                    <?= Html::a('Каталог <i class="fas fa-angle-down ms-1"></i>', ['/product/index'], [
                        'class' => 'nav-link dropdown-toggle' . (Yii::$app->controller->id === 'product' ? ' active' : ''),
                        'data-bs-toggle' => 'dropdown',
                        'role' => 'button'
                    ]) ?>
                    <div class="dropdown-menu mega-menu">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <?php
                                        $topCategories = \app\models\Category::find()
                                            ->orderBy(['name' => SORT_ASC])
                                            ->limit(12)
                                            ->all();
                                        
                                        $chunkedCategories = array_chunk($topCategories, 4);
                                        foreach ($chunkedCategories as $categoryChunk):
                                        ?>
                                            <div class="col-md-6">
                                                <div class="mega-menu-column">
                                                    <?php foreach ($categoryChunk as $category): ?>
                                                        <?= Html::a(
                                                            Html::encode($category->name) . ' <span class="category-count">(' . $category->getProducts()->count() . ')</span>',
                                                            ['/product/index', 'category_id' => $category->id],
                                                            ['class' => 'mega-menu-item']
                                                        ) ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mega-menu-promo">
                                        <div class="promo-card">
                                            <div class="promo-content">
                                                <h5>Специальное предложение!</h5>
                                                <p>Скидки до 50% на популярные товары</p>
                                                <?= Html::a('Смотреть акции', ['/site/sale'], ['class' => 'btn btn-promo']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item">
                    <?= Html::a('Акции', ['/site/sale'], [
                        'class' => 'nav-link sale-link'
                    ]) ?>
                </li>
                
                <li class="nav-item">
                    <?= Html::a('О нас', ['/site/about'], [
                        'class' => 'nav-link' . (Yii::$app->controller->id === 'site' && Yii::$app->controller->action->id === 'about' ? ' active' : '')
                    ]) ?>
                </li>
                
                <li class="nav-item">
                    <?= Html::a('Контакты', ['/site/contact'], [
                        'class' => 'nav-link' . (Yii::$app->controller->id === 'site' && Yii::$app->controller->action->id === 'contact' ? ' active' : '')
                    ]) ?>
                </li>
            </ul>

            <!-- Поиск -->
            <div class="navbar-search me-3">
                <form class="search-form" action="<?= Url::to(['/product/search']) ?>" method="get">
                    <div class="search-input-group">
                        <input type="text" name="q" class="search-input" placeholder="Поиск товаров..." 
                               value="<?= Html::encode(Yii::$app->request->get('q', '')) ?>">
                        <button type="submit" class="search-button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Пользовательские действия -->
            <div class="navbar-actions">
                <!-- Сравнение -->
                <div class="action-item">
                    <a href="<?= Url::to(['/product/compare']) ?>" class="action-link" title="Сравнение">
                        <i class="fas fa-balance-scale"></i>
                        <span class="action-badge compare-count">0</span>
                    </a>
                </div>

                <!-- Избранное -->
                <div class="action-item">
                    <a href="<?= Url::to(['/user/wishlist']) ?>" class="action-link" title="Избранное">
                        <i class="far fa-heart"></i>
                        <span class="action-badge wishlist-count">0</span>
                    </a>
                </div>

                <!-- Корзина -->
                <div class="action-item cart-dropdown">
                    <a href="<?= Url::to(['/cart']) ?>" class="action-link cart-toggle" title="Корзина">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="action-badge cart-count"><?= Yii::$app->cart->getTotalCount() ?></span>
                    </a>
                    
                    <!-- Мини корзина -->
                    <div class="cart-dropdown-menu">
                        <div class="cart-dropdown-header">
                            <h6>Корзина</h6>
                        </div>
                        <div class="cart-dropdown-body" id="mini-cart-items">
                            <!-- Содержимое корзины будет загружаться AJAX -->
                            <div class="cart-empty">
                                <i class="fas fa-shopping-cart"></i>
                                <p>Корзина пуста</p>
                            </div>
                        </div>
                        <div class="cart-dropdown-footer">
                            <div class="cart-total">
                                <span>Итого: </span>
                                <strong id="mini-cart-total"><?= Yii::$app->formatter->asCurrency(Yii::$app->cart->getTotalSum(), 'RUB') ?></strong>
                            </div>
                            <div class="cart-actions">
                                <?= Html::a('Корзина', ['/cart'], ['class' => 'btn btn-outline-primary btn-sm']) ?>
                                <?= Html::a('Оформить', ['/order/create'], ['class' => 'btn btn-primary btn-sm']) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Пользователь -->
                <div class="action-item user-dropdown">
                    <?php if (Yii::$app->user->isGuest): ?>
                        <a href="<?= Url::to(['/site/login']) ?>" class="action-link" title="Войти">
                            <i class="far fa-user"></i>
                        </a>
                    <?php else: ?>
                        <div class="dropdown">
                            <a href="#" class="action-link dropdown-toggle" data-bs-toggle="dropdown" title="Профиль">
                                <?php if (Yii::$app->user->identity->avatar): ?>
                                    <img src="<?= Yii::$app->user->identity->avatar ?>" alt="Avatar" class="user-avatar">
                                <?php else: ?>
                                    <i class="fas fa-user"></i>
                                <?php endif; ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end user-menu">
                                <div class="user-menu-header">
                                    <div class="user-info">
                                        <strong><?= Html::encode(Yii::$app->user->identity->getFullName()) ?></strong>
                                        <small><?= Html::encode(Yii::$app->user->identity->email) ?></small>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <?= Html::a('<i class="fas fa-user me-2"></i>Мой профиль', ['/user/profile'], ['class' => 'dropdown-item']) ?>
                                <?= Html::a('<i class="fas fa-shopping-bag me-2"></i>Мои заказы', ['/user/order-history'], ['class' => 'dropdown-item']) ?>
                                <?= Html::a('<i class="fas fa-heart me-2"></i>Избранное', ['/user/wishlist'], ['class' => 'dropdown-item']) ?>
                                <?= Html::a('<i class="fas fa-cog me-2"></i>Настройки', ['/user/settings'], ['class' => 'dropdown-item']) ?>
                                
                                <?php if (Yii::$app->user->identity->isAdmin()): ?>
                                    <div class="dropdown-divider"></div>
                                    <?= Html::a('<i class="fas fa-shield-alt me-2"></i>Админ панель', ['/admin'], ['class' => 'dropdown-item text-danger']) ?>
                                <?php endif; ?>
                                
                                <div class="dropdown-divider"></div>
                                <?= Html::beginForm(['/site/logout'], 'post', ['class' => 'dropdown-item p-0']) ?>
                                    <?= Html::submitButton('<i class="fas fa-sign-out-alt me-2"></i>Выйти', ['class' => 'dropdown-item text-left w-100 border-0 bg-transparent']) ?>
                                <?= Html::endForm() ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Хлебные крошки -->
<?php if (!empty($this->params['breadcrumbs']) && Yii::$app->controller->action->id !== 'index'): ?>
<section class="breadcrumbs-section">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => $this->params['breadcrumbs'],
            'options' => ['class' => 'breadcrumb modern-breadcrumb'],
            'itemTemplate' => '<li class="breadcrumb-item">{link}</li>',
            'activeItemTemplate' => '<li class="breadcrumb-item active">{link}</li>',
        ]) ?>
    </div>
</section>
<?php endif; ?>

<!-- Flash сообщения -->
<?php foreach (Yii::$app->session->getAllFlashes() as $type => $messages): ?>
    <?php foreach ((array) $messages as $message): ?>
        <div class="alert alert-<?= $type === 'error' ? 'danger' : $type ?> alert-dismissible fade show flash-message" role="alert">
            <div class="container">
                <div class="d-flex align-items-center">
                    <i class="fas fa-<?= $type === 'success' ? 'check-circle' : ($type === 'error' ? 'exclamation-triangle' : 'info-circle') ?> me-2"></i>
                    <?= Html::encode($message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endforeach; ?>

<!-- Основной контент -->
<main class="flex-shrink-0">
    <?= $content ?>
</main>

<!-- Футер -->
<footer class="modern-footer mt-auto">
    <!-- Основной контент футера -->
    <div class="footer-main">
        <div class="container">
            <div class="row g-4">
                <!-- О компании -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-section">
                        <div class="footer-brand">
                            <h5>Limaron</h5>
                            <p>Ваш надежный партнер в мире современных покупок. Качество, скорость, удобство - наши главные принципы.</p>
                        </div>
                        
                        <!-- Социальные сети -->
                        <div class="social-links">
                            <a href="#" class="social-link" title="ВКонтакте">
                                <i class="fab fa-vk"></i>
                            </a>
                            <a href="#" class="social-link" title="Telegram">
                                <i class="fab fa-telegram"></i>
                            </a>
                            <a href="#" class="social-link" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="social-link" title="YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Каталог -->
                <div class="col-lg-2 col-md-6">
                    <div class="footer-section">
                        <h6 class="footer-title">Каталог</h6>
                        <ul class="footer-links">
                            <?php
                            $footerCategories = \app\models\Category::find()
                                ->orderBy(['name' => SORT_ASC])
                                ->limit(6)
                                ->all();
                            foreach ($footerCategories as $category):
                            ?>
                                <li>
                                    <?= Html::a(Html::encode($category->name), ['/product/index', 'category_id' => $category->id], ['class' => 'footer-link']) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <!-- Информация -->
                <div class="col-lg-2 col-md-6">
                    <div class="footer-section">
                        <h6 class="footer-title">Информация</h6>
                        <ul class="footer-links">
                            <li><?= Html::a('О компании', ['/site/about'], ['class' => 'footer-link']) ?></li>
                            <li><?= Html::a('Доставка и оплата', ['/site/delivery'], ['class' => 'footer-link']) ?></li>
                            <li><?= Html::a('Возврат товара', ['/site/return'], ['class' => 'footer-link']) ?></li>
                            <li><?= Html::a('Гарантия', ['/site/warranty'], ['class' => 'footer-link']) ?></li>
                            <li><?= Html::a('Публичная оферта', ['/site/offer'], ['class' => 'footer-link']) ?></li>
                            <li><?= Html::a('Политика конфиденциальности', ['/site/privacy'], ['class' => 'footer-link']) ?></li>
                        </ul>
                    </div>
                </div>

                <!-- Контакты -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-section">
                        <h6 class="footer-title">Контакты</h6>
                        <div class="contact-info">
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <div>
                                    <strong>8 (800) 555-35-35</strong>
                                    <small>Бесплатно по России</small>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <div>
                                    <strong>info@limaron.ru</strong>
                                    <small>Служба поддержки</small>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <div>
                                    <strong>Москва, ул. Московская, 20</strong>
                                    <small>Пн-Пт: 9:00-18:00</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Подвал -->
    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="footer-copyright">
                        © <?= date('Y') ?> Limaron. Все права защищены.
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="payment-methods">
                        <span>Принимаем к оплате:</span>
                        <div class="payment-icons">
                            <i class="fab fa-cc-visa"></i>
                            <i class="fab fa-cc-mastercard"></i>
                            <i class="fab fa-cc-paypal"></i>
                            <i class="fab fa-apple-pay"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Кнопка "Наверх" -->
<button class="scroll-to-top" id="scroll-to-top" title="Наверх">
    <i class="fas fa-chevron-up"></i>
</button>

<!-- Онлайн консультант -->
<div class="online-chat" id="online-chat">
    <div class="chat-toggle" id="chat-toggle">
        <i class="fas fa-comments"></i>
        <span class="chat-notification">1</span>
    </div>
    <div class="chat-window">
        <div class="chat-header">
            <h6>Онлайн консультант</h6>
            <button class="chat-close" id="chat-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="chat-body">
            <div class="chat-message bot-message">
                <div class="message-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="message-content">
                    <p>Здравствуйте! Чем могу помочь?</p>
                    <small>Только что</small>
                </div>
            </div>
        </div>
        <div class="chat-footer">
            <div class="chat-input-group">
                <input type="text" class="chat-input" placeholder="Введите сообщение...">
                <button class="chat-send">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<?php $this->endBody() ?>

<!-- JavaScript для layout -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Скрыть прелоадер
    setTimeout(function() {
        document.getElementById('preloader').style.display = 'none';
    }, 1000);

    // Навигация при скролле
    const navbar = document.getElementById('main-navbar');
    let lastScrollTop = 0;
    
    window.addEventListener('scroll', function() {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > 100) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
        
        // Скрытие/показ навбара при скролле
        if (scrollTop > lastScrollTop && scrollTop > 200) {
            navbar.classList.add('nav-hidden');
        } else {
            navbar.classList.remove('nav-hidden');
        }
        lastScrollTop = scrollTop;
    });

    // Кнопка "Наверх"
    const scrollToTop = document.getElementById('scroll-to-top');
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            scrollToTop.classList.add('visible');
        } else {
            scrollToTop.classList.remove('visible');
        }
    });

    scrollToTop.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Переключатель темы
    const themeToggle = document.getElementById('theme-toggle');
    const currentTheme = localStorage.getItem('theme') || 'light';
    
    if (currentTheme === 'dark') {
        document.body.classList.add('dark-theme');
        themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
    }

    themeToggle.addEventListener('click', function() {
        document.body.classList.toggle('dark-theme');
        
        if (document.body.classList.contains('dark-theme')) {
            localStorage.setItem('theme', 'dark');
            themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
        } else {
            localStorage.setItem('theme', 'light');
            themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
        }
    });

    // Онлайн чат
    const chatToggle = document.getElementById('chat-toggle');
    const chatClose = document.getElementById('chat-close');
    const onlineChat = document.getElementById('online-chat');

    chatToggle.addEventListener('click', function() {
        onlineChat.classList.add('active');
    });

    chatClose.addEventListener('click', function() {
        onlineChat.classList.remove('active');
    });

    // Мини корзина
    loadMiniCart();
});

// Функция загрузки мини корзины
function loadMiniCart() {
    // AJAX запрос для загрузки содержимого корзины
    // Здесь должен быть запрос к вашему контроллеру корзины
}

// Поиск с автодополнением
function initSearch() {
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value;
            if (query.length > 2) {
                // AJAX запрос для автодополнения
                // Показ результатов поиска
            }
        });
    }
}
</script>

</body>
</html>
<?php $this->endPage() ?>