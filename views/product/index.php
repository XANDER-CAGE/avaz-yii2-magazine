<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var $products \app\models\Product[] */
/** @var $categories \app\models\Category[] */
/** @var $selectedCategory int|null */

$this->title = 'Каталог товаров';
$this->params['breadcrumbs'][] = $this->title;
$visibleCount = 7;
?>

<div class="container my-5">
    <h1 class="h3 mb-4 d-flex align-items-center">
        <i class="fas fa-boxes me-2 text-primary"></i> <?= Html::encode($this->title) ?>
    </h1>

    <div class="row">
        <!-- Боковое меню категорий -->
        <div class="col-md-3">
            <div class="card shadow-sm rounded mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Категории</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="<?= Url::to(['product/index']) ?>" 
                       class="list-group-item list-group-item-action <?= $selectedCategory === null ? 'active' : '' ?>">
                        Все товары
                    </a>
                    <div id="categoryList">
                        <?php foreach ($categories as $index => $category): ?>
                            <a href="<?= Url::to(['product/index', 'category_id' => $category->id]) ?>"
                               class="list-group-item list-group-item-action category-item <?= $selectedCategory == $category->id ? 'active' : '' ?> <?= $index >= $visibleCount ? 'd-none' : '' ?>">
                                <?= Html::encode($category->name) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <?php if (count($categories) > $visibleCount): ?>
                        <a href="#" id="showMoreCategories" class="list-group-item list-group-item-action text-center text-primary">
                            Показать ещё
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Фильтры -->
            <div class="card shadow-sm rounded">
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
                                <label class="form-check-label" for="in-stock">В наличии</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="new-arrivals">
                                <label class="form-check-label" for="new-arrivals">Новинки</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100">Применить</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Контент товаров -->
        <div class="col-md-9">
            <?php if (empty($products)): ?>
                <div class="alert alert-info">В данной категории товаров пока нет.</div>
            <?php else: ?>
                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown">
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
                            <div class="card h-100 product-card shadow-sm rounded">
                                <div class="product-card-img">
                                    <?php if ($product->image): ?>
                                        <img src="<?= $product->image ?>" class="card-img-top" alt="<?= Html::encode($product->name) ?>">
                                    <?php else: ?>
                                        <div class="no-image-placeholder"><i class="fas fa-image"></i></div>
                                    <?php endif; ?>
                                    <?php if (strtotime($product->created_at) > strtotime('-30 days')): ?>
                                        <span class="badge bg-success product-badge">Новинка</span>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body d-flex flex-column p-3">
                                    <h5 class="card-title product-title">
                                        <a href="<?= Url::to(['product/view', 'id' => $product->id]) ?>">
                                            <?= Html::encode($product->name) ?>
                                        </a>
                                    </h5>
                                    <p class="card-text text-muted small mb-2">
                                        <?= Html::encode($product->category->name ?? '') ?>
                                    </p>
                                    <p class="card-text product-description mb-3">
                                        <?= Html::encode(mb_substr($product->short_description, 0, 100)) ?>...
                                    </p>
                                    <div class="mt-auto">
                                        <p class="product-price mb-3">
                                            <?= $product->price ? Yii::$app->formatter->asCurrency($product->price, 'RUB') : 'Цена по запросу' ?>
                                        </p>
                                        <div class="d-flex justify-content-between">
                                            <a href="<?= Url::to(['product/view', 'id' => $product->id]) ?>" class="btn btn-outline-primary btn-sm">Подробнее</a>
                                            <a href="<?= Url::to(['cart/add', 'id' => $product->id]) ?>" class="btn btn-primary btn-sm add-to-cart" data-id="<?= $product->id ?>">
                                                <i class="fas fa-shopping-cart"></i>
                                            </a>
                                        </div>
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
$this->registerCss(
    ".product-card-img { height: 200px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; overflow: hidden; }
     .product-card-img img { max-height: 100%; object-fit: contain; }
     .product-badge { position: absolute; top: 10px; right: 10px; }
     .product-title a { color: #212529; text-decoration: none; }
     .product-title a:hover { color: #0d6efd; }
     .product-price { font-weight: bold; font-size: 1.1rem; color: #212529; }
     .no-image-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #ccc; font-size: 2rem; }"
);

$this->registerJs(
    "$(function() {
        if (!new URLSearchParams(window.location.search).has('category_id')) {
            localStorage.removeItem('categoryVisibleCount');
        }
        const visibleCount = {$visibleCount};
        let shown = parseInt(localStorage.getItem('categoryVisibleCount')) || visibleCount;
        $('.category-item').each(function(index) {
            if (index < shown) $(this).removeClass('d-none');
        });
        $('#showMoreCategories').on('click', function(e) {
            e.preventDefault();
            const hidden = $('.category-item.d-none');
            const toShow = hidden.slice(0, visibleCount);
            toShow.removeClass('d-none');
            shown += toShow.length;
            localStorage.setItem('categoryVisibleCount', shown);
            if ($('.category-item.d-none').length === 0) $('#showMoreCategories').remove();
        });
    });"
);
?>