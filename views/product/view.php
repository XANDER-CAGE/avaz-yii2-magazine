<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var app\models\Product $product */
/** @var app\models\Product[] $relatedProducts */

$this->title = $product->name;
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['index']];
if ($product->category) {
    $this->params['breadcrumbs'][] = ['label' => $product->category->name, 'url' => ['index', 'category_id' => $product->category_id]];
}
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="product-view">
    <div class="row">
        <!-- Галерея изображений -->
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="product-gallery">
                <div class="main-image mb-3">
                    <?php if ($product->image): ?>
                        <img src="<?= $product->image ?>" alt="<?= Html::encode($product->name) ?>" class="img-fluid">
                    <?php else: ?>
                        <img src="/img/no-image.jpg" alt="Изображение отсутствует" class="img-fluid">
                    <?php endif; ?>
                </div>
                
                <!-- Здесь можно добавить thumbnails при наличии дополнительных изображений -->
            </div>
        </div>
        
        <!-- Информация о товаре -->
        <div class="col-md-6">
            <h1 class="product-title"><?= Html::encode($product->name) ?></h1>
            
            <?php if ($product->category): ?>
                <div class="product-category">
                    <a href="<?= Url::to(['index', 'category_id' => $product->category_id]) ?>">
                        <?= Html::encode($product->category->name) ?>
                    </a>
                </div>
            <?php endif; ?>
            
            <?php if ($product->sku): ?>
                <div class="product-sku mb-3">
                    Артикул: <span><?= Html::encode($product->sku) ?></span>
                </div>
            <?php endif; ?>
            
            <div class="product-price mb-4">
                <?php if ($product->price): ?>
                    <span class="current-price"><?= $product->getFormattedPrice() ?></span>
                <?php else: ?>
                    <span class="price-on-request">Цена по запросу</span>
                <?php endif; ?>
            </div>
            
            <div class="product-description mb-4">
                <?php if ($product->short_description): ?>
                    <div class="short-description mb-3">
                        <?= Html::encode($product->short_description) ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="product-actions mb-4">
                <form id="add-to-cart-form" class="mb-3">
                    <div class="row align-items-center">
                        <div class="col-4 col-sm-3">
                            <div class="quantity-controls">
                                <button type="button" class="btn btn-outline-secondary quantity-down">-</button>
                                <input type="number" class="form-control quantity-input" value="1" min="1" name="quantity">
                                <button type="button" class="btn btn-outline-secondary quantity-up">+</button>
                            </div>
                        </div>
                        <div class="col-8 col-sm-9">
                            <button type="submit" class="btn btn-primary add-to-cart w-100" data-id="<?= $product->id ?>">
                                В корзину
                            </button>
                        </div>
                    </div>
                </form>
                
                <div class="d-flex">
                    <button class="btn btn-outline-secondary me-2 w-50" id="add-to-wishlist" data-id="<?= $product->id ?>">
                        <i class="far fa-heart"></i> В избранное
                    </button>
                    <button class="btn btn-outline-secondary w-50" id="share-product">
                        <i class="fas fa-share-alt"></i> Поделиться
                    </button>
                </div>
            </div>
            
            <?php if ($product->full_description): ?>
                <div class="product-full-description">
                    <h3 class="section-title">Описание</h3>
                    <div class="content">
                        <?= nl2br(Html::encode($product->full_description)) ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Доставка и оплата -->
            <div class="product-delivery-info mt-4">
                <div class="delivery-item">
                    <i class="fas fa-truck me-2"></i>
                    <span>Доставка: 1-3 дня</span>
                </div>
                <div class="delivery-item">
                    <i class="fas fa-credit-card me-2"></i>
                    <span>Оплата: наличные, карта, онлайн</span>
                </div>
                <div class="delivery-item">
                    <i class="fas fa-shield-alt me-2"></i>
                    <span>Гарантия качества</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Вкладки с дополнительной информацией -->
    <div class="product-tabs mt-5">
        <ul class="nav nav-tabs" id="productTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" 
                        type="button" role="tab" aria-controls="description" aria-selected="true">
                    Подробности
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" 
                        type="button" role="tab" aria-controls="specifications" aria-selected="false">
                    Характеристики
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" 
                        type="button" role="tab" aria-controls="reviews" aria-selected="false">
                    Отзывы
                </button>
            </li>
        </ul>
        <div class="tab-content p-4 border border-top-0" id="productTabContent">
            <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                <?php if ($product->full_description): ?>
                    <?= nl2br(Html::encode($product->full_description)) ?>
                <?php else: ?>
                    <p>Подробное описание отсутствует.</p>
                <?php endif; ?>
            </div>
            <div class="tab-pane fade" id="specifications" role="tabpanel" aria-labelledby="specifications-tab">
                <p>Информация о характеристиках товара будет добавлена в ближайшее время.</p>
            </div>
            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                <p>Отзывы о товаре отсутствуют.</p>
                <button class="btn btn-outline-primary">Оставить отзыв</button>
            </div>
        </div>
    </div>
    
    <!-- Похожие товары -->
    <?php if (!empty($relatedProducts)): ?>
    <div class="related-products mt-5">
        <h3 class="section-title">Похожие товары</h3>
        <div class="row">
            <?php foreach ($relatedProducts as $relatedProduct): ?>
                <div class="col-md-3 mb-4">
                    <div class="product-card">
                        <div class="product-image">
                            <a href="<?= Url::to(['product/view', 'id' => $relatedProduct->id]) ?>">
                                <?php if ($relatedProduct->image): ?>
                                    <img src="<?= $relatedProduct->image ?>" alt="<?= Html::encode($relatedProduct->name) ?>" class="img-fluid">
                                <?php else: ?>
                                    <img src="/img/no-image.jpg" alt="Изображение отсутствует" class="img-fluid">
                                <?php endif; ?>
                            </a>
                        </div>
                        
                        <div class="product-info p-3">
                            <h3 class="product-title">
                                <a href="<?= Url::to(['product/view', 'id' => $relatedProduct->id]) ?>">
                                    <?= Html::encode($relatedProduct->name) ?>
                                </a>
                            </h3>
                            
                            <div class="product-price">
                                <?php if ($relatedProduct->price): ?>
                                    <span class="current-price"><?= Yii::$app->formatter->asCurrency($relatedProduct->price, 'RUB') ?></span>
                                <?php else: ?>
                                    <span class="price-on-request">Цена по запросу</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php
$css = <<<CSS
    /* Стили страницы товара */
    .product-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    .product-category {
        margin-bottom: 1rem;
    }
    
    .product-category a {
        color: var(--secondary-color);
        text-decoration: none;
        font-size: 0.9rem;
    }
    
    .product-sku {
        font-size: 0.9rem;
        color: var(--secondary-color);
    }
    
    .product-price {
        font-size: 1.5rem;
        font-weight: 600;
    }
    
    .price-on-request {
        font-style: italic;
        font-size: 1.2rem;
    }
    
    .product-gallery .main-image {
        border: 1px solid var(--border-color);
        background-color: var(--light-grey);
    }
    
    .quantity-controls {
        display: flex;
        align-items: center;
    }
    
    .quantity-controls .form-control {
        text-align: center;
        border-radius: 0;
        height: 42px;
        width: 100%;
        padding: 0.375rem 0.5rem;
    }
    
    .quantity-controls .btn {
        border-radius: 0;
        height: 42px;
        padding: 0.375rem 0.75rem;
        line-height: 1;
    }
    
    .delivery-item {
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
    }
    
    .delivery-item i {
        color: var(--accent-color);
        width: 20px;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1.25rem;
        position: relative;
    }
    
    .nav-tabs {
        border-bottom: 1px solid var(--border-color);
    }
    
    .nav-tabs .nav-link {
        border: none;
        border-bottom: 2px solid transparent;
        border-radius: 0;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        color: var(--secondary-color);
        transition: all 0.3s ease;
    }
    
    .nav-tabs .nav-link:hover {
        color: var(--primary-color);
    }
    
    .nav-tabs .nav-link.active {
        color: var(--primary-color);
        background-color: transparent;
        border-bottom: 2px solid var(--accent-color);
    }
    
    .product-full-description {
        border-top: 1px solid var(--border-color);
        padding-top: 1.5rem;
        margin-top: 1.5rem;
    }
    
    .product-full-description .content {
        line-height: 1.6;
    }
    
    .related-products .section-title {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    /* Адаптивность */
    @media (max-width: 767px) {
        .product-actions .btn {
            padding: 0.5rem 1rem;
        }
    }
CSS;

$this->registerCss($css);

$js = <<<JS
    // Функционал изменения количества товара
    $('.quantity-down').on('click', function() {
        var input = $('.quantity-input');
        var value = parseInt(input.val());
        if (value > 1) {
            input.val(value - 1);
        }
    });
    
    $('.quantity-up').on('click', function() {
        var input = $('.quantity-input');
        var value = parseInt(input.val());
        input.val(value + 1);
    });
    
    // Добавление товара в корзину
    $('#add-to-cart-form').on('submit', function(e) {
        e.preventDefault();
        var productId = $('.add-to-cart').data('id');
        var quantity = $('.quantity-input').val();
        
        $.ajax({
            url: '/cart/add',
            type: 'GET',
            data: {
                id: productId,
                quantity: quantity
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Обновляем счетчик товаров в корзине
                    if (response.count) {
                        $('.cart-count').text(response.count);
                        $('.cart-count').show();
                    }
                    
                    // Показываем уведомление
                    $('<div class="toast-notification">').html('Товар добавлен в корзину')
                        .appendTo('body')
                        .fadeIn(300)
                        .delay(2000)
                        .fadeOut(300, function() {
                            $(this).remove();
                        });
                } else {
                    alert('Ошибка при добавлении товара в корзину');
                }
            },
            error: function() {
                alert('Произошла ошибка при обращении к серверу');
            }
        });
    });
    
    // Добавление в избранное
    $('#add-to-wishlist').on('click', function() {
        var icon = $(this).find('i');
        
        if (icon.hasClass('far')) {
            icon.removeClass('far').addClass('fas');
            
            // Показываем уведомление
            $('<div class="toast-notification">').html('Товар добавлен в избранное')
                .appendTo('body')
                .fadeIn(300)
                .delay(2000)
                .fadeOut(300, function() {
                    $(this).remove();
                });
        } else {
            icon.removeClass('fas').addClass('far');
            
            // Показываем уведомление
            $('<div class="toast-notification">').html('Товар удален из избранного')
                .appendTo('body')
                .fadeIn(300)
                .delay(2000)
                .fadeOut(300, function() {
                    $(this).remove();
                });
        }
    });
    
    // Функционал поделиться товаром
    $('#share-product').on('click', function() {
        // Здесь можно реализовать всплывающее окно с соц. сетями
        alert('Функция "Поделиться" будет доступна в ближайшее время');
    });
    
    // Стили для всплывающего уведомления
    $('<style>').html(`
        .toast-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: var(--primary-color);
            color: white;
            padding: 12px 20px;
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1050;
            display: none;
            font-size: 14px;
        }
    `).appendTo('head');
JS;

$this->registerJs($js);
?>