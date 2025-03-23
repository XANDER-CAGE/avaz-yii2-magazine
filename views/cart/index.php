<?php
use yii\helpers\Html;

/** @var $items array */
/** @var $total float */

$this->title = 'Корзина';
?>

<h1>Корзина</h1>

<?php if (empty($items)): ?>
    <p>Корзина пуста</p>
<?php else: ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Товар</th>
                <th>Цена</th>
                <th>Количество</th>
                <th>Сумма</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= Html::encode($item['product']->name) ?></td>
                <td><?= $item['product']->price ?> ₽</td>
                <td><?= $item['quantity'] ?></td>
                <td><?= $item['sum'] ?> ₽</td>
                <td><?= Html::a('Удалить', ['cart/remove', 'id' => $item['product']->id], ['class' => 'btn btn-sm btn-danger']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"><strong>Итого:</strong></td>
                <td colspan="2"><strong><?= $total ?> ₽</strong></td>
            </tr>
        </tfoot>
    </table>

    <p>
        <?= Html::a('Очистить корзину', ['cart/clear'], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Оформить заказ', ['order/create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php endif; ?>
