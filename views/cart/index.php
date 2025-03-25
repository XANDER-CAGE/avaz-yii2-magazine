<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/** @var array $items */
/** @var float $total */
$this->title = 'Корзина';
?>

<div class="cart-page my-5 bg-white p-4 rounded shadow-sm">
    <h1 class="section-title mb-4">Корзина</h1>

    <?php if (empty($items)): ?>
        <div class="alert alert-info text-center">
            <p class="mb-3">Ваша корзина пуста.</p>
            <?= Html::a('Перейти в каталог', ['/product/index'], ['class' => 'btn btn-primary']) ?>
        </div>
    <?php else: ?>
        <form id="cart-form">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Товар</th>
                            <th class="text-center">Цена</th>
                            <th class="text-center">Количество</th>
                            <th class="text-center">Сумма</th>
                            <th class="text-center">Удалить</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr data-id="<?= $item['product']->id ?>">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?= $item['product']->image ?: '/img/no-image.jpg' ?>" alt="<?= Html::encode($item['product']->name) ?>" class="me-3" width="64">
                                        <div>
                                            <strong><?= Html::encode($item['product']->name) ?></strong><br>
                                            <small class="text-muted">ID: <?= $item['product']->id ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <?= Yii::$app->formatter->asCurrency($item['product']->price, 'RUB') ?>
                                </td>
                                <td class="text-center">
                                    <input type="number" class="form-control form-control-sm quantity-input" value="<?= $item['quantity'] ?>" min="1">
                                </td>
                                <td class="text-center item-sum">
                                    <?= Yii::$app->formatter->asCurrency($item['product']->price * $item['quantity'], 'RUB') ?>
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-sm btn-outline-danger remove-item" data-id="<?= $item['product']->id ?>">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="cart-summary mt-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <?= Html::a('Очистить корзину', ['/cart/clear'], [
                            'class' => 'btn btn-outline-secondary',
                            'data-confirm' => 'Очистить корзину?',
                            'data-method' => 'post'
                        ]) ?>
                    </div>
                    <div class="text-end">
                        <p class="mb-1"><strong>Итого:</strong> <span id="cart-total"><?= Yii::$app->formatter->asCurrency($total, 'RUB') ?></span></p>
                        <?= Html::a('Оформить заказ', ['/order/create'], ['class' => 'btn btn-primary btn-lg']) ?>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php
$js = <<<JS
$('.quantity-input').on('change', function () {
    var row = $(this).closest('tr');
    var id = row.data('id');
    var quantity = parseInt($(this).val());

    if (quantity < 1) quantity = 1;

    $.post('/cart/update-quantity', {id: id, quantity: quantity}, function (res) {
        if (res.success) {
            row.find('.item-sum').text(res.item_sum);
            $('#cart-total').text(res.total);
            $('.cart-count').text(res.totalCount);
        }
    });
});

$('.remove-item').on('click', function (e) {
    e.preventDefault();
    var btn = $(this);
    var id = btn.data('id');

    $.get('/cart/remove', {id: id}, function (res) {
        if (res.success) {
            btn.closest('tr').fadeOut(function () {
                $(this).remove();
                $('#cart-total').text(res.total);
                $('.cart-count').text(res.totalCount);
                if ($('tbody tr').length === 0) {
                    location.reload();
                }
            });
        }
    });
});
JS;
$this->registerJs($js);
?>
