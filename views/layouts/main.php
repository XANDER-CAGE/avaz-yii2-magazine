<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use app\models\Category;
use app\models\Product;
use yii\helpers\Url;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? 'Подарки для тех, у кого есть всё. Эксклюзивные товары и VIP-услуги.']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? 'подарки, эксклюзив, vip, звезды, именные подарки']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);

// Получаем все категории из базы данных
$categories = Category::find()->all();

// Регистрируем Google шрифты
$this->registerCssFile('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap');

// Регистрируем иконки Font Awesome
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css');

// Дополнительные стили для нашего премиум-дизайна
$customCss = <<<CSS
    body {
        font-family: 'Montserrat', sans-serif;
        color: #333;
        background-color: #fff;
    }
    
    h1, h2, h3, h4, h5, h6 {
        font-family: 'Playfair Display', serif;
    }
    
    .stargift-header {
        padding: 15px 0;
        border-bottom: 1px solid #e9e9e9;
    }
    
    .stargift-logo {
        font-family: 'Playfair Display', serif;
        font-weight: 600;
        font-size: 2.5rem;
        color: #333;
        text-decoration: none;
        display: inline-block;
    }
    
    .stargift-slogan {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.9rem;
        color: #a38e65;
        letter-spacing: 1px;
        font-weight: 300;
        text-transform: uppercase;
    }
    
    .delivery-info {
        display: inline-block;
        font-size: 0.9rem;
        color: #666;
    }
    
    .phone-number {
        display: inline-block;
        padding: 8px 15px;
        background-color: #f8f8f8;
        border-radius: 3px;
        font-weight: 500;
        color: #333;
    }
    
    .language-selector {
        display: inline-block;
        margin-left: 15px;
    }
    
    .cart-container {
        display: inline-block;
        position: relative;
        margin-left: 15px;
    }
    
    .cart-link {
        color: #333;
        font-size: 18px;
    }
    
    .cart-link:hover {
        color: #a38e65;
        text-decoration: none;
    }
    
    .cart-count {
        position: absolute;
        top: -8px;
        right: -8px;
        background-color: #a38e65;
        color: white;
        font-size: 11px;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .main-menu {
        background-color: #fff;
        border-bottom: 1px solid #e9e9e9;
        font-family: 'Montserrat', sans-serif;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
    }
    
    .main-menu .nav-link {
        color: #333;
        padding: 15px;
        font-weight: 500;
    }
    
    .main-menu .nav-link:hover {
        color: #a38e65;
    }
    
    .catalog-submenu {
        background-color: #f5f5f5;
        padding: 15px 0;
        text-align: center;
    }
    
    .catalog-submenu .nav-link {
        color: #333;
        padding: 5px 15px;
        font-size: 0.85rem;
    }
    
    .catalog-submenu .nav-link:hover,
    .catalog-submenu .nav-link.active {
        color: #a38e65;
    }
    
    .category-filters {
        background-color: #fff;
        padding: 15px 0;
        text-align: center;
        border-bottom: 1px solid #e9e9e9;
    }
    
    .category-filters .nav-link {
        color: #333;
        padding: 5px 15px;
        font-size: 0.85rem;
    }
    
    .category-filters .nav-link:hover,
    .category-filters .nav-link.active {
        color: #a38e65;
    }
    
    .main-title {
        font-family: 'Playfair Display', serif;
        font-size: 3rem;
        color: #a38e65;
        margin: 30px 0;
        text-align: center;
    }
    
    /* Кнопка Telegram в углу */
    .telegram-button {
        position: fixed;
        left: 20px;
        top: 20px;
        width: 50px;
        height: 50px;
        background-color: #0088cc;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        z-index: 1000;
    }
    
    .telegram-button:hover {
        background-color: #0077b5;
        color: white;
        text-decoration: none;
    }
    
    /* Стили для footer */
    .footer {
        background-color: #f8f8f8;
        border-top: 1px solid #e9e9e9;
    }
    
    .footer h5 {
        font-family: 'Playfair Display', serif;
        font-size: 1.25rem;
        color: #333;
        margin-bottom: 20px;
    }
    
    .footer p {
        color: #666;
    }
    
    .social-icons a {
        color: #333;
        font-size: 1.5rem;
        transition: color 0.3s ease;
    }
    
    .social-icons a:hover {
        color: #a38e65;
    }
    
    /* Стили для пагинации */
    .pagination .page-link {
        color: #a38e65;
    }
    
    .pagination .active .page-link {
        background-color: #a38e65;
        border-color: #a38e65;
    }
    
    /* Стили для кнопок */
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

$this->registerCss($customCss);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<!-- Telegram кнопка -->
<a href="https://t.me/stargift" class="telegram-button" title="Напишите нам в Telegram">
    <i class="fab fa-telegram-plane"></i>
</a>

<!-- Верхняя часть с логотипом и информацией -->
<div class="stargift-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4">
                <a href="<?= Yii::$app->homeUrl ?>" class="stargift-logo">Stargift</a>
                <div class="stargift-slogan">Подарок для того, у кого есть всё</div>
            </div>
            <div class="col-md-4 text-center">
                <div class="delivery-info">
                    <i class="fas fa-shipping-fast mr-2"></i> Доставка по всему миру
                </div>
            </div>
            <div class="col-md-4 text-right d-flex justify-content-end align-items-center">
                <div class="phone-number">+7 495 877 4288</div>
                <div class="language-selector">
                    <a href="#" class="active">Рус</a> / 
                    <a href="#">En</a>
                </div>
                <div class="cart-container">
                    <a href="<?= Url::to(['/cart/index']) ?>" class="cart-link">
                        <i class="fas fa-shopping-cart"></i>
                        <?php 
                        $cartCount = 0;
                        if (isset(Yii::$app->cart) && method_exists(Yii::$app->cart, 'getItems')) {
                            $cartCount = count(Yii::$app->cart->getItems());
                        }
                        ?>
                        <?php if ($cartCount > 0): ?>
                            <span class="cart-count"><?= $cartCount ?></span>
                        <?php endif; ?>
                    </a>
                </div>
                
                <?php if (Yii::$app->user->isGuest): ?>
                    <div class="login-container ml-3">
                        <a href="<?= Url::to(['/site/login']) ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-user mr-1"></i> Войти
                        </a>
                    </div>
                <?php else: ?>
                    <div class="user-container ml-3 dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle mr-1"></i> <?= Html::encode(Yii::$app->user->identity->username) ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="<?= Url::to(['/profile/index']) ?>">
                                <i class="fas fa-user mr-2"></i> Мой профиль
                            </a>
                            <a class="dropdown-item" href="<?= Url::to(['/order/my-orders']) ?>">
                                <i class="fas fa-shopping-bag mr-2"></i> Мои заказы
                            </a>
                            <div class="dropdown-divider"></div>
                            <?= Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline dropdown-item p-0']) ?>
                            <?= Html::submitButton(
                                '<i class="fas fa-sign-out-alt mr-2"></i> Выйти',
                                ['class' => 'btn btn-link dropdown-item text-danger']
                            ) ?>
                            <?= Html::endForm() ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Основное меню -->
<div class="main-menu">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light p-0">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainMenu" aria-controls="mainMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="mainMenu">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item <?= Yii::$app->controller->id == 'product' ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= Url::to(['/product/index']) ?>">Каталог</a>
                    </li>
                    <li class="nav-item <?= Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'vip' ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= Url::to(['/site/vip']) ?>">VIP-услуги</a>
                    </li>
                    <li class="nav-item <?= Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'yoomoota' ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= Url::to(['/site/yoomoota']) ?>">YOOMOOTA</a>
                    </li>
                    <li class="nav-item <?= Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'about' ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= Url::to(['/site/about']) ?>">О нас</a>
                    </li>
                    <li class="nav-item <?= Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'press' ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= Url::to(['/site/press']) ?>">Пресса</a>
                    </li>
                    <li class="nav-item <?= Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'stars' ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= Url::to(['/site/stars']) ?>">Звезды с нами</a>
                    </li>
                    <li class="nav-item <?= Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'events' ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= Url::to(['/site/events']) ?>">Мероприятия</a>
                    </li>
                    <li class="nav-item <?= Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'charity' ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= Url::to(['/site/charity']) ?>">Благотворительность</a>
                    </li>
                    <li class="nav-item <?= Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'contact' ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= Url::to(['/site/contact']) ?>">Контакты</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>

<?php if (Yii::$app->controller->id == 'product' || (Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'index')): ?>
<!-- Подменю каталога -->
<div class="catalog-submenu">
    <div class="container">
        <nav class="nav justify-content-center">
            <a class="nav-link <?= !isset($_GET['filter']) ? 'active' : '' ?>" 
               href="<?= Url::to(['/product/index']) ?>">Все</a>
            <a class="nav-link <?= isset($_GET['filter']) && $_GET['filter'] == 'new' ? 'active' : '' ?>" 
               href="<?= Url::to(['/product/index', 'filter' => 'new']) ?>">Новинки</a>
            <a class="nav-link <?= isset($_GET['filter']) && $_GET['filter'] == 'exclusive' ? 'active' : '' ?>" 
               href="<?= Url::to(['/product/index', 'filter' => 'exclusive']) ?>">Эксклюзив</a>
            <a class="nav-link <?= isset($_GET['filter']) && $_GET['filter'] == 'name' ? 'active' : '' ?>" 
               href="<?= Url::to(['/product/index', 'filter' => 'name']) ?>">Именные обращения</a>
            <a class="nav-link <?= isset($_GET['filter']) && $_GET['filter'] == 'meetings' ? 'active' : '' ?>" 
               href="<?= Url::to(['/product/index', 'filter' => 'meetings']) ?>">Встречи</a>
            <a class="nav-link <?= isset($_GET['filter']) && $_GET['filter'] == 'yoomoota' ? 'active' : '' ?>" 
               href="<?= Url::to(['/product/index', 'filter' => 'yoomoota']) ?>">YOOMOOTA</a>
        </nav>
    </div>
</div>

<!-- Категории фильтров -->
<div class="category-filters">
    <div class="container">
        <nav class="nav justify-content-center">
            <?php foreach ($categories as $category): ?>
                <a class="nav-link <?= isset($_GET['category_id']) && $_GET['category_id'] == $category->id ? 'active' : '' ?>" 
                   href="<?= Url::to(['/product/index', 'category_id' => $category->id]) ?>">
                    <?= Html::encode($category->name) ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </div>
    
    <div class="container mt-3">
        <nav class="nav justify-content-center">
            <a class="nav-link <?= isset($_GET['for']) && $_GET['for'] == 'men' ? 'active' : '' ?>" 
               href="<?= Url::to(['/product/index', 'for' => 'men']) ?>">Мужчине</a>
            <a class="nav-link <?= isset($_GET['for']) && $_GET['for'] == 'women' ? 'active' : '' ?>" 
               href="<?= Url::to(['/product/index', 'for' => 'women']) ?>">Женщине</a>
            <a class="nav-link <?= isset($_GET['for']) && $_GET['for'] == 'child' ? 'active' : '' ?>" 
               href="<?= Url::to(['/product/index', 'for' => 'child']) ?>">Ребенку</a>
            <a class="nav-link <?= isset($_GET['for']) && $_GET['for'] == 'boss' ? 'active' : '' ?>" 
               href="<?= Url::to(['/product/index', 'for' => 'boss']) ?>">Руководителю</a>
            <a class="nav-link <?= isset($_GET['for']) && $_GET['for'] == 'wedding' ? 'active' : '' ?>" 
               href="<?= Url::to(['/product/index', 'for' => 'wedding']) ?>">Свадьба</a>
            <a class="nav-link <?= isset($_GET['for']) && $_GET['for'] == 'russia' ? 'active' : '' ?>" 
               href="<?= Url::to(['/product/index', 'for' => 'russia']) ?>">Россия</a>
            <a class="nav-link <?= isset($_GET['for']) && $_GET['for'] == 'things' ? 'active' : '' ?>" 
               href="<?= Url::to(['/product/index', 'for' => 'things']) ?>">Вещи</a>
            <a class="nav-link <?= isset($_GET['for']) && $_GET['for'] == 'books' ? 'active' : '' ?>" 
               href="<?= Url::to(['/product/index', 'for' => 'books']) ?>">Книги</a>
            <a class="nav-link <?= isset($_GET['for']) && $_GET['for'] == 'expensive' ? 'active' : '' ?>" 
               href="<?= Url::to(['/product/index', 'for' => 'expensive']) ?>">Дороже 1 млн</a>
        </nav>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($this->params['breadcrumbs'])): ?>
<div class="container mt-3">
    <?= Breadcrumbs::widget([
        'links' => $this->params['breadcrumbs'],
        'options' => ['class' => 'breadcrumb small']
    ]) ?>
</div>
<?php endif; ?>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-5 py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>Stargift</h5>
                <p class="small">Подарок для того, у кого есть всё</p>
                <p class="small">© <?= date('Y') ?> Stargift. Все права защищены.</p>
            </div>
            <div class="col-md-4">
                <h5>Контакты</h5>
                <p class="small"><i class="fas fa-phone-alt mr-2"></i> +7 495 877 4288</p>
                <p class="small"><i class="fas fa-envelope mr-2"></i> info@stargift.ru</p>
                <p class="small"><i class="fas fa-map-marker-alt mr-2"></i> Москва, ул. Примерная, 123</p>
            </div>
            <div class="col-md-4">
                <h5>Мы в социальных сетях</h5>
                <div class="social-icons">
                    <a href="#" class="mr-3"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="mr-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="mr-3"><i class="fab fa-telegram-plane"></i></a>
                    <a href="#" class="mr-3"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>