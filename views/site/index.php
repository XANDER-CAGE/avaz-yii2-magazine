<?php
/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Category;
use app\models\Product;

// Get all categories
$categories = Category::find()->all();

// Get featured products (limited to 6 for the homepage)
$featuredProducts = Product::find()->limit(6)->all();

?>

<!-- КАТАЛОГ -->
<div class="catalog-section mb-5">
    <h2 class="section-title mb-4">Каталог</h2>
    <div class="row">
        <!-- Категории (боковое меню) -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Категории</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="<?= Url::to(['product/index']) ?>" 
                       class="list-group-item list-group-item-action">
                        Все товары
                    </a>
                    <?php foreach ($categories as $category): ?>
                        <a href="<?= Url::to(['product/index', 'category_id' => $category->id]) ?>" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <?= Html::encode($category->name) ?>
                            <span class="badge bg-primary rounded-pill">
                                <?= Product::find()->where(['category_id' => $category->id])->count() ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Фильтр по цене -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Цена</h5>
                </div>
                <div class="card-body">
                    <form action="<?= Url::to(['product/index']) ?>" method="get">
                        <div class="row g-3">
                            <div class="col-6">
                                <input type="number" name="price_from" class="form-control form-control-sm" placeholder="от">
                            </div>
                            <div class="col-6">
                                <input type="number" name="price_to" class="form-control form-control-sm" placeholder="до">
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary btn-sm w-100">Применить</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Акции и специальные предложения -->
            <div class="card mt-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Скидки и акции</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">Получите скидку 10% при первом заказе!</p>
                    <a href="<?= Url::to(['site/sale']) ?>" class="btn btn-outline-danger btn-sm w-100">Все акции</a>
                </div>
            </div>
        </div>

        <!-- Товары каталога -->
        <div class="col-md-9">
            <div class="row">
                <?php foreach ($featuredProducts as $product): ?>
                    <div class="col-md-4 mb-4">
                        <div class="product-card h-100 border rounded overflow-hidden shadow-sm">
                            <div class="product-image position-relative" style="padding-top: 120%;">
                                <?php if ($product->image): ?>
                                    <img src="<?= $product->image ?>" alt="<?= Html::encode($product->name) ?>" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover">
                                <?php else: ?>
                                    <img src="/img/no-image.jpg" alt="<?= Html::encode($product->name) ?>" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover">
                                <?php endif; ?>
                                
                                <?php if (strtotime($product->created_at) > strtotime('-30 days')): ?>
                                    <div class="product-badge position-absolute top-0 end-0 bg-success text-white px-2 py-1 m-2 small">Новинка</div>
                                <?php endif; ?>
                            </div>
                            <div class="product-info p-3">
                                <?php if ($product->category): ?>
                                    <div class="product-category text-muted small mb-1">
                                        <?= Html::encode($product->category->name) ?>
                                    </div>
                                <?php endif; ?>
                                <h6 class="product-title mb-2">
                                    <a href="<?= Url::to(['product/view', 'id' => $product->id]) ?>" class="text-dark">
                                        <?= Html::encode($product->name) ?>
                                    </a>
                                </h6>
                                <div class="product-price mb-3">
                                    <?php if ($product->price): ?>
                                        <span class="fw-bold"><?= Yii::$app->formatter->asCurrency($product->price, 'RUB') ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">Цена по запросу</span>
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <a href="<?= Url::to(['product/view', 'id' => $product->id]) ?>" class="btn btn-outline-primary btn-sm">Подробнее</a>
                                    <a href="<?= Url::to(['cart/add', 'id' => $product->id]) ?>" class="btn btn-primary btn-sm add-to-cart" data-id="<?= $product->id ?>">
                                        <i class="fas fa-shopping-cart"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Кнопка "Показать все товары" -->
            <div class="text-center mt-4">
                <a href="<?= Url::to(['product/index']) ?>" class="btn btn-primary">
                    Смотреть весь каталог
                    <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<?php
$js = <<<JS
    // Добавление товара в корзину
    $('.add-to-cart').on('click', function(e) {
        e.preventDefault();
        
        var btn = $(this);
        var productId = btn.data('id');
        
        $.ajax({
            url: btn.attr('href'),
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Показываем уведомление об успешном добавлении
                    var notification = $('<div class="alert alert-success alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;" role="alert">')
                        .html('Товар добавлен в корзину <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>')
                        .appendTo('body');
                    
                    // Автоматически скрываем через 3 секунды
                    setTimeout(function() {
                        notification.alert('close');
                    }, 3000);
                    
                    // Обновляем счетчик товаров в корзине, если он есть
                    var cartCount = $('.cart-count');
                    if (cartCount.length && response.count) {
                        cartCount.text(response.count);
                    }
                } else {
                    // Показываем ошибку
                    alert('Произошла ошибка при добавлении товара в корзину');
                }
            },
            error: function() {
                alert('Произошла ошибка при обращении к серверу');
            }
        });
    });
JS;

$this->registerJs($js);
?>