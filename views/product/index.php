<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var $products \app\models\Product[] */
/** @var $categories \app\models\Category[] */
/** @var $selectedCategory int|null */

$this->title = 'Каталог товаров';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="product-catalog">
    <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>
    
    <div class="row mb-4">
        <!-- Боковое меню категорий -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Категории</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="<?= Url::to(['product/index']) ?>" 
                       class="list-group-item list-group-item-action <?= $selectedCategory === null ? 'active' : '' ?>">
                        Все товары
                    </a>
                    <?php foreach ($categories as $category): ?>
                        <a href="<?= Url::to(['product/index', 'category_id' => $category->id]) ?>" 
                           class="list-group-item list-group-item-action <?= $selectedCategory == $category->id ? 'active' : '' ?>">
                            <?= Html::encode($category->name) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Фильтры (можно добавить при необходимости) -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Фильтры</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Цена</label>
                            <div class="d-flex">
                                <input type="number" class="form-control form-control-sm me-2" placeholder="от">
                                <input type="number" class="form-control form-control-sm" placeholder="до">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="in-stock">
                                <label class="form-check-label" for="in-stock">
                                    В наличии
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="new-arrivals">
                                <label class="form-check-label" for="new-arrivals">
                                    Новинки
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-sm w-100">Применить</button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Товары -->
        <div class="col-md-9">
            <?php if (empty($products)): ?>
                <div class="alert alert-info">
                    В данной категории товаров пока нет.
                </div>
            <?php else: ?>
                <!-- Сортировка и вид отображения -->
                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" 
                                id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Сортировать
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                            <li><a class="dropdown-item" href="<?= Url::to(['product/index', 'category_id' => $selectedCategory, 'sort' => 'name']) ?>">По названию</a></li>
                            <li><a class="dropdown-item" href="<?= Url::to(['product/index', 'category_id' => $selectedCategory, 'sort' => 'price']) ?>">По цене (возрастание)</a></li>
                            <li><a class="dropdown-item" href="<?= Url::to(['product/index', 'category_id' => $selectedCategory, 'sort' => '-price']) ?>">По цене (убывание)</a></li>
                            <li><a class="dropdown-item" href="<?= Url::to(['product/index', 'category_id' => $selectedCategory, 'sort' => '-created_at']) ?>">Сначала новые</a></li>
                        </ul>
                    </div>
                    
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary btn-sm active" data-view="grid">
                            <i class="fas fa-th"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-view="list">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Сетка товаров -->
                <div class="row" id="products-grid">
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 product-card">
                                <div class="product-card-img">
                                    <?php if ($product->image): ?>
                                        <img src="<?= $product->image ?>" class="card-img-top" alt="<?= Html::encode($product->name) ?>">
                                    <?php else: ?>
                                        <div class="no-image-placeholder">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (strtotime($product->created_at) > strtotime('-30 days')): ?>
                                        <span class="badge bg-success product-badge">Новинка</span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title product-title">
                                        <a href="<?= Url::to(['product/view', 'id' => $product->id]) ?>">
                                            <?= Html::encode($product->name) ?>
                                        </a>
                                    </h5>
                                    
                                    <?php if ($product->category): ?>
                                        <p class="card-text text-muted small mb-2">
                                            <?= Html::encode($product->category->name) ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <p class="card-text product-description mb-3">
                                        <?= Html::encode(mb_substr($product->short_description, 0, 100)) ?>...
                                    </p>
                                    
                                    <div class="mt-auto">
                                        <p class="product-price mb-3">
                                            <?= $product->price ? Yii::$app->formatter->asCurrency($product->price, 'RUB') : 'Цена по запросу' ?>
                                        </p>
                                        
                                        <div class="d-flex justify-content-between">
                                            <a href="<?= Url::to(['product/view', 'id' => $product->id]) ?>" class="btn btn-outline-primary btn-sm">
                                                Подробнее
                                            </a>
                                            <a href="<?= Url::to(['cart/add', 'id' => $product->id]) ?>" 
                                               class="btn btn-primary btn-sm add-to-cart"
                                               data-id="<?= $product->id ?>">
                                                <i class="fas fa-shopping-cart"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Список товаров (скрыт по умолчанию) -->
                <div class="list-group d-none" id="products-list">
                    <?php foreach ($products as $product): ?>
                        <div class="list-group-item list-group-item-action product-list-item">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <div class="product-list-img">
                                        <?php if ($product->image): ?>
                                            <img src="<?= $product->image ?>" class="img-fluid" alt="<?= Html::encode($product->name) ?>">
                                        <?php else: ?>
                                            <div class="no-image-placeholder">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <h5 class="mb-1 product-title">
                                        <a href="<?= Url::to(['product/view', 'id' => $product->id]) ?>">
                                            <?= Html::encode($product->name) ?>
                                        </a>
                                    </h5>
                                    
                                    <?php if ($product->category): ?>
                                        <p class="text-muted small mb-2">
                                            <?= Html::encode($product->category->name) ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <p class="mb-1 product-description">
                                        <?= Html::encode(mb_substr($product->short_description, 0, 120)) ?>...
                                    </p>
                                </div>
                                <div class="col-md-3 text-end">
                                    <p class="product-price mb-2">
                                        <?= $product->price ? Yii::$app->formatter->asCurrency($product->price, 'RUB') : 'Цена по запросу' ?>
                                    </p>
                                    
                                    <div class="d-flex justify-content-end">
                                        <a href="<?= Url::to(['product/view', 'id' => $product->id]) ?>" class="btn btn-outline-primary btn-sm me-2">
                                            Подробнее
                                        </a>
                                        <a href="<?= Url::to(['cart/add', 'id' => $product->id]) ?>" 
                                           class="btn btn-primary btn-sm add-to-cart"
                                           data-id="<?= $product->id ?>">
                                            <i class="fas fa-shopping-cart"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Пагинация -->
                <?php if (isset($pages)): ?>
                    <div class="d-flex justify-content-center mt-4">
                        <?= \yii\widgets\LinkPager::widget([
                            'pagination' => $pages,
                            'options' => ['class' => 'pagination'],
                            'linkContainerOptions' => ['class' => 'page-item'],
                            'linkOptions' => ['class' => 'page-link'],
                            'disabledListItemSubTagOptions' => ['class' => 'page-link']
                        ]) ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$css = <<<CSS
    /* Стили для страницы каталога */
    .product-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border: 1px solid rgba(0,0,0,0.1);
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .product-card-img {
        position: relative;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background-color: #f8f9fa;
    }
    
    .product-card-img img {
        max-height: 100%;
        object-fit: contain;
    }
    
    .no-image-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        width: 100%;
        color: #ccc;
        font-size: 3rem;
    }
    
    .product-badge {
        position: absolute;
        top: 10px;
        right: 10px;
    }
    
    .product-title a {
        color: #212529;
        text-decoration: none;
        transition: color 0.3s;
    }
    
    .product-title a:hover {
        color: #0d6efd;
    }
    
    .product-description {
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .product-price {
        font-weight: bold;
        font-size: 1.1rem;
        color: #212529;
    }
    
    .product-list-img {
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .product-list-img img {
        max-height: 100%;
        object-fit: contain;
    }
    
    .product-list-item {
        transition: background-color 0.3s;
    }
    
    .product-list-item:hover {
        background-color: #f8f9fa;
    }
CSS;

$this->registerCss($css);

$js = <<<JS
    // Переключение между сеткой и списком
    $('[data-view]').on('click', function() {
        var view = $(this).data('view');
        
        // Активация кнопки
        $('[data-view]').removeClass('active');
        $(this).addClass('active');
        
        if (view === 'grid') {
            $('#products-grid').removeClass('d-none');
            $('#products-list').addClass('d-none');
        } else {
            $('#products-grid').addClass('d-none');
            $('#products-list').removeClass('d-none');
        }
    });
    
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