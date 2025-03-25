<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var array $items Товары в корзине */
/** @var float $total Общая сумма */
/** @var int $maxItems Максимальное количество товаров для отображения */
/** @var string $containerClass CSS-класс контейнера */
/** @var string $viewCartText Текст для ссылки "Перейти в корзину" */
/** @var string $checkoutText Текст для ссылки "Оформить заказ" */
/** @var string $emptyCartText Текст для пустой корзины */
?>

<div class="<?= $containerClass ?>">
    <div class="mini-cart-content">
        <div class="mini-cart-header">
            <h6 class="mb-0">Корзина <span class="text-muted">(<?= count($items) ?>)</span></h6>
            <button type="button" class="btn-close mini-cart-close" aria-label="Close"></button>
        </div>
        
        <?php if (empty($items)): ?>
            <div class="mini-cart-empty">
                <div class="text-center py-4">
                    <i class="fas fa-shopping-cart fa-2x text-muted mb-3"></i>
                    <p><?= Html::encode($emptyCartText) ?></p>
                    <?= Html::a('Перейти в каталог', ['/product/index'], ['class' => 'btn btn-sm btn-primary']) ?>
                </div>
            </div>
        <?php else: ?>
            <div class="mini-cart-items">
                <?php 
                $counter = 0;
                foreach ($items as $item): 
                    if ($counter++ >= $maxItems) break;
                ?>
                    <div class="mini-cart-item">
                        <div class="mini-cart-item-image">
                            <?php if ($item['product']->image): ?>
                                <img src="<?= $item['product']->image ?>" alt="<?= Html::encode($item['product']->name) ?>" class="img-fluid">
                            <?php else: ?>
                                <div class="no-image"><i class="fas fa-image"></i></div>
                            <?php endif; ?>
                        </div>
                        <div class="mini-cart-item-info">
                            <h6 class="mini-cart-item-title">
                                <?= Html::a(
                                    Html::encode($item['product']->name),
                                    ['/product/view', 'id' => $item['product']->id]
                                ) ?>
                            </h6>
                            <div class="mini-cart-item-price">
                                <?= $item['quantity'] ?> × <?= Yii::$app->formatter->asCurrency($item['product']->price, 'RUB') ?>
                            </div>
                        </div>
                        <div class="mini-cart-item-remove">
                            <?= Html::a('<i class="fas fa-times"></i>', ['/cart/remove', 'id' => $item['product']->id], [
                                'class' => 'mini-cart-remove-item',
                                'data-id' => $item['product']->id,
                                'title' => 'Удалить',
                            ]) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <?php if (count($items) > $maxItems): ?>
                    <div class="mini-cart-more">
                        <small class="text-muted">
                            И ещё <?= count($items) - $maxItems ?> <?= Yii::t('app', '{n, plural, =1{товар} one{товар} few{товара} many{товаров} other{товаров}}', ['n' => count($items) - $maxItems]) ?>
                        </small>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="mini-cart-footer">
                <div class="mini-cart-total">
                    <span>Итого:</span>
                    <span class="mini-cart-total-sum"><?= Yii::$app->formatter->asCurrency($total, 'RUB') ?></span>
                </div>
                <div class="mini-cart-actions">
                    <?= Html::a(Html::encode($viewCartText), ['/cart/index'], ['class' => 'btn btn-outline-primary btn-sm']) ?>
                    <?= Html::a(Html::encode($checkoutText), ['/order/create'], ['class' => 'btn btn-primary btn-sm']) ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>