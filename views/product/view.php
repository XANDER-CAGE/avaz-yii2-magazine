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

<div class="product-page">
    <div class="row">
        <!-- Изображение -->
        <div class="col-md-6 mb-4">
            <div class="product-image-wrapper">
                <img src="<?= $product->image ?: '/img/no-image.jpg' ?>" alt="<?= Html::encode($product->name) ?>" class="img-fluid rounded">
            </div>
        </div>

        <!-- Информация -->
        <div class="col-md-6">
            <h1 class="product-title"><?= Html::encode($product->name) ?></h1>

            <div class="product-meta text-muted mb-2">
                <?php if ($product->sku): ?>Артикул: <?= Html::encode($product->sku) ?><?php endif; ?>
            </div>

            <div class="product-price h4 fw-bold mb-3">
                <?= $product->getFormattedPrice() ?>
            </div>

            <!-- Цвет -->
            <div class="mb-4">
                <div class="form-label">Выберите цвет</div>
                <div class="d-flex gap-2">
                    <div class="color-circle bg-black"></div>
                    <div class="color-circle bg-primary"></div>
                    <div class="color-circle bg-danger"></div>
                    <div class="color-circle bg-white border"></div>
                </div>
            </div>

            <!-- Размер -->
            <div class="mb-4">
                <div class="form-label">Выберите размер</div>
                <div class="d-flex flex-wrap gap-2">
                    <div class="size-box">35<br><small>22,5 см</small></div>
                    <div class="size-box">36<br><small>23 см</small></div>
                    <div class="size-box">37<br><small>23,5 см</small></div>
                    <div class="size-box">38<br><small>24 см</small></div>
                </div>
            </div>

            <!-- Кнопка -->
            <div class="mb-4">
                <a href="<?= Url::to(['/cart/add', 'id' => $product->id]) ?>" class="btn btn-success btn-lg w-100">В корзину</a>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="product-tabs mt-5">
        <ul class="nav nav-pills mb-3" id="product-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-about" data-bs-toggle="pill" data-bs-target="#about" type="button" role="tab">О товаре</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-reviews" data-bs-toggle="pill" data-bs-target="#reviews" type="button" role="tab">Отзывы</button>
            </li>
        </ul>
        <div class="tab-content" id="product-tab-content">
            <!-- О товаре -->
            <div class="tab-pane fade show active" id="about" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Дополнительная информация</h5>
                        <ul class="list-unstyled">
                            <li>Ширина упаковки: 30 см</li>
                            <li>Высота упаковки: 12 см</li>
                            <li>Вес: 600 г</li>
                            <li>Страна производства: Китай</li>
                            <li>Высота подошвы: 4 см</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-3">Описание</h5>
                        <p>
                            <?= nl2br(Html::encode($product->full_description ?: 'Описание отсутствует.')) ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Отзывы -->
            <div class="tab-pane fade" id="reviews" role="tabpanel">
                <p>Пока отзывов нет.</p>
                <button class="btn btn-outline-primary">Оставить отзыв</button>
            </div>
        </div>
    </div>
</div>
