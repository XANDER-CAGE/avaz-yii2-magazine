<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var app\models\Product $product */

$this->title = $product->name;
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['index']];
if ($product->category) {
    $this->params['breadcrumbs'][] = ['label' => $product->category->name, 'url' => ['index', 'category_id' => $product->category_id]];
}
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="product-view mt-4">
    <div class="row">
        <div class="col-md-6">
            <div class="product-gallery">
                <?php if ($product->image): ?>
                    <div class="main-image mb-3">
                        <img src="<?= $product->image ?>" alt="<?= Html::encode($product->name) ?>" class="img-fluid">
                    </div>
                    
                    <!-- Здесь можно добавить дополнительные изображения товара, если они есть -->
                <?php else: ?>
                    <div class="main-image mb-3">
                        <img src="/img/no-image.jpg" alt="Изображение отсутствует" class="img-fluid">
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="col-md-6">
            <h1 class="product-title"><?= Html::encode($product->name) ?></h1>
            
            <?php if ($product->category): ?>
                <div class="product-category">
                    <span><?= Html::encode($product->category->name) ?></span>
                </div>
            <?php endif; ?>
            
            <?php if ($product->price): ?>
                <div class="product-price">
                    <?= Yii::$app->formatter->asCurrency($product->price, 'RUB') ?>
                </div>
            <?php else: ?>
                <div class="product-price">
                    <span class="price-on-request">Цена по запросу</span>
                </div>
            <?php endif; ?>
            
            <?php if ($product->sku): ?>
                <div class="product-sku">
                    <span>Артикул: <?= Html::encode($product->sku) ?></span>
                </div>
            <?php endif; ?>
            
            <div class="product-description mt-4">
                <h4>Описание</h4>
                <div class="short-description">
                    <?= Html::encode($product->short_description) ?>
                </div>
                
                <?php if ($product->full_description): ?>
                    <div class="full-description mt-3">
                        <?= nl2br(Html::encode($product->full_description)) ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="product-actions mt-4">
                <div class="d-flex">
                    <div class="quantity mr-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-secondary quantity-down" type="button">-</button>
                            </div>
                            <input type="number" class="form-control quantity-input" value="1" min="1">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary quantity-up" type="button">+</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="add-to-cart-container">
                        <?= Html::a('В корзину', ['cart/add', 'id' => $product->id], [
                            'class' => 'btn btn-primary add-to-cart',
                            'data-id' => $product->id
                        ]) ?>
                    </div>
                </div>
                
                <div class="mt-3">
                    <button class="btn btn-outline-secondary btn-block">
                        <i class="far fa-heart"></i> Добавить в избранное
                    </button>
                </div>
            </div>
            
            <div class="product-delivery mt-4">
                <h4>Доставка</h4>
                <p><i class="fas fa-shipping-fast mr-2"></i> Доставка по всему миру</p>
                <p><i class="fas fa-box mr-2"></i> Подарочная упаковка</p>
                <p><i class="fas fa-shield-alt mr-2"></i> Гарантия подлинности</p>
            </div>
        </div>
    </div>
    
    <?php if (!empty($relatedProducts)): ?>
    <div class="related-products mt-5">
        <h3 class="section-title">Похожие товары</h3>
        <div class="row">
            <?php foreach ($relatedProducts as $relatedProduct): ?>
                <div class="col-md-3 mb-4">
                    <div class="product-card">
                        <div class="product-image">
                            <?php if ($relatedProduct->image): ?>
                                <img src="<?= $relatedProduct->image ?>" alt="<?= Html::encode($relatedProduct->name) ?>" class="img-fluid">
                            <?php else: ?>
                                <img src="/img/no-image.jpg" alt="Изображение отсутствует" class="img-fluid">
                            <?php endif; ?>
                        </div>
                        
                        <div class="product-info">
                            <h3 class="product-title">
                                <a href="<?= Url::to(['product/view', 'id' => $relatedProduct->id]) ?>">
                                    <?= Html::encode($relatedProduct->name) ?>
                                </a>
                            </h3>
                            
                            <div class="product-price">
                                <?php if ($relatedProduct->price): ?>
                                    <?= Yii::$app->formatter->asCurrency($relatedProduct->price, 'RUB') ?>
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
    /* Стили для страницы товара */
    .product-title {
        font-family: 'Playfair Display', serif;
        font-size: 32px;
        margin-bottom: 15px;
        color: #333;
    }
    
    .product-category {
        font-size: 14px;
        color: #888;
        margin-bottom: 15px;
    }
    
    .product-price {
        font-size: 24px;
        font-weight: 600;
        color: #a38e65;
        margin-bottom: 15px;
    }
    
    .price-on-request {
        font-style: italic;
        font-size: 20px;
    }
    
    .product-sku {
        font-size: 14px;
        color: #888;
        margin-bottom: 15px;
    }
    
    .product-gallery .main-image {
        border: 1px solid #e9e9e9;
        padding: 5px;
        background-color: #fff;
    }
    
    .product-gallery .main-image img {
        width: 100%;
        height: auto;
    }
    
    .product-description h4 {
        font-family: 'Playfair Display', serif;
        font-size: 20px;
        margin-bottom: 15px;
        color: #333;
    }
    
    .short-description {
        font-size: 16px;
        color: #666;
        margin-bottom: 15px;
    }
    
    .full-description {
        font-size: 14px;
        color: #666;
        line-height: 1.6;
    }
    
    .quantity .form-control {
        text-align: center;
        width: 60px;
    }
    
    .quantity .btn {
        width: 40px;
    }
    
    .product-delivery h4 {
        font-family: 'Playfair Display', serif;
        font-size: 20px;
        margin-bottom: 15px;
        color: #333;
    }
    
    .product-delivery p {
        font-size: 14px;
        color: #666;
        margin-bottom: 8px;
    }
    
    .section-title {
        font-family: 'Playfair Display', serif;
        font-size: 24px;
        color: #333;
        margin-bottom: 20px;
        text-align: center;
    }
    
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

$this->registerCss($css);

$js = <<<JS
    // Функционал изменения количества товара
    $('.quantity-down').on('click', function() {
        var input = $(this).closest('.input-group').find('.quantity-input');
        var value = parseInt(input.val()) - 1;
        if (value >= 1) {
            input.val(value);
        }
    });
    
    $('.quantity-up').on('click', function() {
        var input = $(this).closest('.input-group').find('.quantity-input');
        var value = parseInt(input.val()) + 1;
        input.val(value);
    });
    
    // Добавление товара в корзину через AJAX
    $('.add-to-cart').on('click', function(e) {
        e.preventDefault();
        var btn = $(this);
        var productId = btn.data('id');
        var quantity = $('.quantity-input').val();
        
        $.ajax({
            url: btn.attr('href'),
            type: 'GET',
            data: {quantity: quantity},
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Товар добавлен в корзину');
                    // Здесь можно обновить счетчик товаров в корзине
                    // $('.cart-count').text(response.count);
                } else {
                    alert('Ошибка при добавлении товара в корзину');
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