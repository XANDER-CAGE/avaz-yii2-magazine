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
    ->limit(10)
    ->all();
$featuredProducts = Product::find()->limit(6)->all();

?>

<div class="catalog-section mb-5">
    <h2 class="section-title">Каталог</h2>
    <div class="row">
        <!-- Боковая панель -->
        <div class="col-lg-3 mb-4 mb-lg-0">
            <!-- Категории -->
<div class="mb-4">
    <h5 class="fw-bold mb-3">Категории</h5>
    <ul class="list-unstyled">
        <li class="mb-2">
            <a href="<?= Url::to(['product/index']) ?>" class="d-flex justify-content-between text-decoration-none text-dark">
                <span>Все товары</span>
                <span class="badge bg-primary rounded-pill">
                    <?= Product::find()->count() ?>
                </span>
            </a>
        </li>
        <?php foreach ($categories as $category): ?>
            <li class="mb-2">
                <a href="<?= Url::to(['product/index', 'category_id' => $category->id]) ?>"
                   class="d-flex justify-content-between text-decoration-none text-dark">
                    <span><?= Html::encode($category->name) ?></span>
                    <span class="badge bg-primary rounded-pill">
                        <?= $category->getProducts()->count() ?>
                    </span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>


            
        </div>

        <!-- Товары -->
        <div class="col-lg-9">
            <div class="row g-4">
                <?php foreach ($featuredProducts as $product): ?>
                    <div class="col-6 col-md-4">
                        <div class="product-card h-100">
                            <!-- Изображение -->
                            <div class="product-image position-relative" style="padding-top: 120%;">
                                <img src="<?= $product->image ?: '/img/no-image.jpg' ?>"
                                     alt="<?= Html::encode($product->name) ?>"
                                     class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover">
                                
                                <?php if (strtotime($product->created_at) > strtotime('-30 days')): ?>
                                    <div class="product-badge position-absolute top-0 end-0 bg-success text-white px-2 py-1 m-2 small">Новинка</div>
                                <?php endif; ?>
                            </div>

                            <!-- Инфо -->
                            <div class="product-info">
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

            <!-- Кнопка "Смотреть всё" -->
            <div class="text-center mt-5">
                <a href="<?= Url::to(['product/index']) ?>" class="btn btn-primary btn-lg">
                    Смотреть весь каталог <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<?php
$js = <<<JS
    // Ajax добавление товара в корзину
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
                    var notification = $('<div class="alert alert-success alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;" role="alert">')
                        .html('Товар добавлен в корзину <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>')
                        .appendTo('body');

                    setTimeout(function() {
                        notification.alert('close');
                    }, 3000);

                    var cartCount = $('.cart-count');
                    if (cartCount.length && response.count) {
                        cartCount.text(response.count);
                    }
                } else {
                    alert('Ошибка при добавлении товара в корзину');
                }
            },
            error: function() {
                alert('Ошибка при обращении к серверу');
            }
        });
    });
JS;

$this->registerJs($js);
?>
