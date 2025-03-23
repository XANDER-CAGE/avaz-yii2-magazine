<?php
/** @var yii\web\View $this */
/** @var app\models\Product[] $products */
/** @var app\models\Category[] $categories */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Каталог';
?>

<h1 class="main-title">Каталог</h1>

<?php if (empty($products)): ?>
    <div class="alert alert-info text-center">
        В данный момент товаров в этой категории нет.
    </div>
<?php else: ?>

<div class="row mt-4">
    <?php foreach ($products as $product): ?>
    <div class="col-md-4 mb-4">
        <div class="product-card">
            <div class="product-image">
                <?php if ($product->image): ?>
                    <img src="<?= $product->image ?>" alt="<?= Html::encode($product->name) ?>" class="img-fluid">
                <?php else: ?>
                    <img src="/img/no-image.jpg" alt="Изображение отсутствует" class="img-fluid">
                <?php endif; ?>
                
                <?php if (strtotime($product->created_at) > strtotime('-30 days')): ?>
                    <span class="product-badge new">Новинка</span>
                <?php endif; ?>
            </div>
            
            <div class="product-info">
                <h3 class="product-title">
                    <a href="<?= Url::to(['product/view', 'id' => $product->id]) ?>">
                        <?= Html::encode($product->name) ?>
                    </a>
                </h3>
                
                <div class="product-category">
                    <?php if ($product->category): ?>
                        <span><?= Html::encode($product->category->name) ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="product-description">
                    <?= Html::encode(mb_substr($product->short_description, 0, 100)) ?>...
                </div>
                
                <div class="product-price">
                    <?php if ($product->price): ?>
                        <?= Yii::$app->formatter->asCurrency($product->price, 'RUB') ?>
                    <?php else: ?>
                        <span class="price-on-request">Цена по запросу</span>
                    <?php endif; ?>
                </div>
                
                <div class="product-actions">
                    <?= Html::a('Подробнее', ['product/view', 'id' => $product->id], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                    <?= Html::a('В корзину', ['cart/add', 'id' => $product->id], [
                        'class' => 'btn btn-primary btn-sm add-to-cart',
                        'data-id' => $product->id
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="text-center mt-4">
    <?= \yii\widgets\LinkPager::widget([
        'pagination' => $pages,
        'options' => ['class' => 'pagination justify-content-center'],
        'linkContainerOptions' => ['class' => 'page-item'],
        'linkOptions' => ['class' => 'page-link'],
        'disabledListItemSubTagOptions' => ['class' => 'page-link']
    ]) ?>
</div>

<?php endif; ?>

<?php
$css = <<<CSS
    /* Стили для карточек товаров */
    .product-card {
        border: 1px solid #e9e9e9;
        border-radius: 5px;
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        background-color: #fff;
    }
    
    .product-card:hover {
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transform: translateY(-5px);
    }
    
    .product-image {
        position: relative;
        padding-top: 75%;
        overflow: hidden;
    }
    
    .product-image img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .product-card:hover .product-image img {
        transform: scale(1.05);
    }
    
    .product-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 5px 10px;
        font-size: 12px;
        border-radius: 3px;
        z-index: 1;
    }
    
    .product-badge.new {
        background-color: #a38e65;
        color: white;
    }
    
    .product-info {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    
    .product-title {
        font-family: 'Playfair Display', serif;
        font-size: 18px;
        margin-bottom: 10px;
    }
    
    .product-title a {
        color: #333;
        text-decoration: none;
    }
    
    .product-title a:hover {
        color: #a38e65;
    }
    
    .product-category {
        font-size: 13px;
        color: #888;
        margin-bottom: 10px;
    }
    
    .product-description {
        font-size: 14px;
        color: #666;
        margin-bottom: 15px;
        flex-grow: 1;
    }
    
    .product-price {
        font-size: 18px;
        font-weight: 600;
        color: #a38e65;
        margin-bottom: 15px;
    }
    
    .price-on-request {
        font-style: italic;
        font-size: 16px;
    }
    
    .product-actions {
        display: flex;
        justify-content: space-between;
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
    
    /* Стиль для пагинации */
    .pagination .page-link {
        color: #a38e65;
    }
    
    .pagination .active .page-link {
        background-color: #a38e65;
        border-color: #a38e65;
    }
CSS;

$this->registerCss($css);

$js = <<<JS
    // Добавление товара в корзину через AJAX
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