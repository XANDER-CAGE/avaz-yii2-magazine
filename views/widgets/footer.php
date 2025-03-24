<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var \app\models\Category[] $categories */
?>

<footer class="footer mt-auto">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <h5>О магазине</h5>
                <p class="mb-3">Интернет-магазин с уникальными товарами и отличным сервисом. У нас вы найдете всё необходимое.</p>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-telegram"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                <h5>Информация</h5>
                <ul class="footer-links">
                    <li><a href="<?= Url::to(['/site/about']) ?>">О нас</a></li>
                    <li><a href="<?= Url::to(['/site/contact']) ?>">Контакты</a></li>
                    <li><a href="<?= Url::to(['/site/delivery']) ?>">Доставка</a></li>
                    <li><a href="<?= Url::to(['/site/payment']) ?>">Оплата</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                <h5>Каталог</h5>
                <ul class="footer-links">
                    <?php foreach ($categories as $category): ?>
                        <li>
                            <a href="<?= Url::to(['/product/index', 'category_id' => $category->id]) ?>">
                                <?= Html::encode($category->name) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col-lg-4 col-md-4">
                <h5>Контакты</h5>
                <p><i class="fas fa-phone me-2"></i> +7 495 123 4567</p>
                <p><i class="fas fa-envelope me-2"></i> info@store.ru</p>
                <p><i class="fas fa-map-marker-alt me-2"></i> Москва, ул. Примерная, 1</p>
            </div>
        </div>
        <div class="row mt-4 pt-3 border-top">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0">© <?= date('Y') ?> Store. Все права защищены.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="mb-0">Разработано на Yii2 Framework</p>
            </div>
        </div>
    </div>
</footer>
