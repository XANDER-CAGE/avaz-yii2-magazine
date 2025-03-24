<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'Импорт товаров из Tilda';
$this->params['breadcrumbs'][] = ['label' => 'Управление товарами', 'url' => ['/admin/product']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="card-body">
                    <?php if (Yii::$app->session->hasFlash('success')): ?>
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-check"></i> Успешно!</h5>
                            <?= Yii::$app->session->getFlash('success') ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (Yii::$app->session->hasFlash('error')): ?>
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-ban"></i> Ошибка!</h5>
                            <?= Yii::$app->session->getFlash('error') ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (Yii::$app->session->hasFlash('warning')): ?>
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-exclamation-triangle"></i> Внимание!</h5>
                            <?= Yii::$app->session->getFlash('warning') ?>
                        </div>
                    <?php endif; ?>
                    
                    <h4>Настройки импорта</h4>
                    
                    <?php $form = ActiveForm::begin(['id' => 'import-settings-form', 'method' => 'get']); ?>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Очистка HTML</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="cleanHtml" name="cleanHtml" value="1" checked>
                                    <label class="custom-control-label" for="cleanHtml">Очищать HTML-теги в описаниях</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Обновление товаров</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="updateExisting" name="updateExisting" value="1" checked>
                                    <label class="custom-control-label" for="updateExisting">Обновлять существующие товары</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Импорт категорий</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="importCategories" name="importCategories" value="1" checked>
                                    <label class="custom-control-label" for="importCategories">Импортировать категории из Tilda</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php ActiveForm::end(); ?>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-info">
                                    <h5 class="card-title"><i class="fas fa-download"></i> Импорт товаров</h5>
                                </div>
                                <div class="card-body">
                                    <p>Загрузка товаров из Tilda API в базу данных магазина.</p>
                                    <div class="btn-group">
                                        <?= Html::a('<i class="fas fa-download"></i> Импортировать первую страницу', 
                                            ['import-from-api'], 
                                            [
                                                'class' => 'btn btn-info',
                                                'id' => 'btn-import-page',
                                                'data-method' => 'post'
                                            ]
                                        ) ?>
                                        <?= Html::a('<i class="fas fa-download"></i> Импортировать все товары', 
                                            ['import-all-from-api'], 
                                            [
                                                'class' => 'btn btn-primary',
                                                'id' => 'btn-import-all',
                                                'data' => [
                                                    'method' => 'post',
                                                    'confirm' => 'Вы действительно хотите импортировать все товары из Tilda? Это может занять некоторое время.'
                                                ]
                                            ]
                                        ) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-danger">
                                    <h5 class="card-title"><i class="fas fa-trash"></i> Очистка импортированных товаров</h5>
                                </div>
                                <div class="card-body">
                                    <p>Удаление всех товаров, ранее импортированных из Tilda (с SKU, начинающимся на "tilda_").</p>
                                    <?= Html::a('<i class="fas fa-trash"></i> Удалить импортированные товары', 
                                        ['clear-imported'], 
                                        [
                                            'class' => 'btn btn-danger',
                                            'data' => [
                                                'method' => 'post',
                                                'confirm' => 'Вы уверены, что хотите удалить все товары, импортированные из Tilda? Это действие нельзя отменить.'
                                            ]
                                        ]
                                    ) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-secondary">
                                    <h5 class="card-title"><i class="fas fa-bug"></i> Отладка API</h5>
                                </div>
                                <div class="card-body">
                                    <p>Отображение технической информации о структуре API Tilda и данных товаров.</p>
                                    <?= Html::a('<i class="fas fa-bug"></i> Отладка API структуры', 
                                        ['debug-api'], 
                                        [
                                            'class' => 'btn btn-secondary',
                                            'target' => '_blank'
                                        ]
                                    ) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$js = <<<JS
// Обработка кнопок импорта с учетом настроек
$('#btn-import-page, #btn-import-all').on('click', function(e) {
    e.preventDefault();
    
    var url = $(this).attr('href');
    var cleanHtml = $('#cleanHtml').is(':checked') ? 1 : 0;
    var updateExisting = $('#updateExisting').is(':checked') ? 1 : 0;
    var importCategories = $('#importCategories').is(':checked') ? 1 : 0;
    
    // Формируем URL с параметрами
    var fullUrl = url + '?cleanHtml=' + cleanHtml + '&updateExisting=' + updateExisting + '&importCategories=' + importCategories;
    
    // Если нужно подтверждение
    if ($(this).data('confirm')) {
        if (confirm($(this).data('confirm'))) {
            // Отправляем POST запрос
            var form = $('<form>', {
                'method': 'POST',
                'action': fullUrl
            });
            
            // Добавляем CSRF токен
            form.append($('<input>', {
                'name': yii.getCsrfParam(),
                'value': yii.getCsrfToken(),
                'type': 'hidden'
            }));
            
            $('body').append(form);
            form.submit();
        }
    } else {
        // Отправляем POST запрос без подтверждения
        var form = $('<form>', {
            'method': 'POST',
            'action': fullUrl
        });
        
        // Добавляем CSRF токен
        form.append($('<input>', {
            'name': yii.getCsrfParam(),
            'value': yii.getCsrfToken(),
            'type': 'hidden'
        }));
        
        $('body').append(form);
        form.submit();
    }
});
JS;

$this->registerJs($js);
?>