<?php

return [
    // Основные настройки приложения
    'adminEmail' => 'admin@limaron.ru',
    'supportEmail' => 'support@limaron.ru',
    'noreplyEmail' => 'no-reply@limaron.ru',
    'senderEmail' => 'no-reply@limaron.ru',
    'senderName' => 'Limaron Store',
    
    // Настройки пользователей
    'user.passwordResetTokenExpire' => 3600, // 1 час
    'user.passwordMinLength' => 8,
    'user.rememberMeDuration' => 3600 * 24 * 30, // 30 дней
    'enableEmailVerification' => false, // Включить верификацию email при регистрации
    
    // Настройки корзины и заказов
    'freeDeliveryThreshold' => 3000, // Сумма для бесплатной доставки
    'deliveryCost' => 300, // Стоимость доставки
    'maxCartItems' => 50, // Максимальное количество товаров в корзине
    'cartSessionDuration' => 3600 * 24 * 7, // 7 дней хранения корзины в сессии
    
    // Настройки товаров
    'productsPerPage' => 12, // Количество товаров на странице каталога
    'productImageMaxSize' => 2 * 1024 * 1024, // 2MB - максимальный размер изображения товара
    'productImageAllowedTypes' => ['image/jpeg', 'image/png', 'image/webp'],
    'productImageSizes' => [
        'thumb' => [150, 150],
        'medium' => [300, 300],
        'large' => [800, 600],
    ],
    
    // Настройки отзывов
    'reviewsEnabled' => true,
    'reviewsModeration' => true, // Требуется модерация отзывов
    'reviewsPerPage' => 10,
    'maxReviewLength' => 2000,
    
    // Настройки SEO
    'siteName' => 'Limaron',
    'siteDescription' => 'Современный интернет-магазин с широким ассортиментом товаров',
    'siteKeywords' => 'интернет-магазин, товары, покупки, доставка',
    'defaultMetaImage' => '/img/logo-social.jpg',
    
    // Настройки безопасности
    'passwordHash' => [
        'cost' => 13, // Сложность хеширования пароля
    ],
    'rateLimit' => [
        'attempts' => 5, // Количество попыток
        'duration' => 300, // 5 минут блокировки
    ],
    
    // Настройки файлов
    'uploadPath' => '@webroot/uploads',
    'uploadUrl' => '/uploads',
    'maxFileSize' => 10 * 1024 * 1024, // 10MB
    'allowedFileTypes' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx'],
    
    // Настройки кеширования
    'cacheEnabled' => true,
    'cacheDuration' => 3600, // 1 час
    'pageCacheDuration' => 300, // 5 минут для страниц
    
    // Настройки уведомлений
    'notifications' => [
        'newOrder' => true, // Уведомления о новых заказах
        'lowStock' => true, // Уведомления о низком остатке товаров
        'newReview' => true, // Уведомления о новых отзывах
        'stockThreshold' => 5, // Порог для уведомлений о низком остатке
    ],
    
    // Настройки оплаты
    'paymentMethods' => [
        'card' => 'Банковская карта',
        'cash' => 'Наличные при получении',
        'online' => 'Онлайн-платежи',
        'bank_transfer' => 'Банковский перевод',
    ],
    
    // Настройки доставки
    'deliveryMethods' => [
        'courier' => [
            'name' => 'Курьерская доставка',
            'cost' => 300,
            'freeFrom' => 3000,
            'minDays' => 1,
            'maxDays' => 3,
        ],
        'pickup' => [
            'name' => 'Самовывоз',
            'cost' => 0,
            'freeFrom' => 0,
            'minDays' => 0,
            'maxDays' => 1,
        ],
        'post' => [
            'name' => 'Почта России',
            'cost' => 250,
            'freeFrom' => 5000,
            'minDays' => 3,
            'maxDays' => 14,
        ],
    ],
    
    // Настройки статусов заказов
    'orderStatuses' => [
        'pending' => [
            'name' => 'Ожидает обработки',
            'color' => 'warning',
            'icon' => 'fa-clock',
            'canCancel' => true,
        ],
        'processing' => [
            'name' => 'В обработке',
            'color' => 'info',
            'icon' => 'fa-cog',
            'canCancel' => true,
        ],
        'shipped' => [
            'name' => 'Отправлен',
            'color' => 'primary',
            'icon' => 'fa-truck',
            'canCancel' => false,
        ],
        'delivered' => [
            'name' => 'Доставлен',
            'color' => 'success',
            'icon' => 'fa-check',
            'canCancel' => false,
        ],
        'cancelled' => [
            'name' => 'Отменен',
            'color' => 'danger',
            'icon' => 'fa-times',
            'canCancel' => false,
        ],
    ],
    
    // Настройки социальных сетей
    'socialNetworks' => [
        'vk' => 'https://vk.com/limaron_store',
        'telegram' => 'https://t.me/limaron_store',
        'instagram' => 'https://instagram.com/limaron_store',
        'youtube' => 'https://youtube.com/c/limaron_store',
    ],
    
    // Контактная информация
    'contacts' => [
        'phone' => '8 (800) 555-35-35',
        'email' => 'info@limaron.ru',
        'address' => 'Москва, ул. Московская, 20',
        'workingHours' => 'Пн-Пт: 9:00-18:00, Сб-Вс: 10:00-16:00',
        'coordinates' => [55.7558, 37.6176], // Широта, долгота для карты
    ],
    
    // Настройки аналитики
    'analytics' => [
        'googleAnalytics' => '', // GA_TRACKING_ID
        'yandexMetrica' => '', // Yandex Metrica ID
        'facebookPixel' => '', // Facebook Pixel ID
    ],
    
    // Настройки интеграций
    'integrations' => [
        'sms' => [
            'enabled' => false,
            'provider' => 'smsru', // smsru, smsc, etc.
            'apiKey' => '',
        ],
        'cdek' => [
            'enabled' => false,
            'account' => '',
            'password' => '',
        ],
        'robokassa' => [
            'enabled' => false,
            'merchantLogin' => '',
            'password1' => '',
            'password2' => '',
            'testMode' => true,
        ],
    ],
    
    // Настройки производительности
    'performance' => [
        'enableGzip' => true,
        'enableAssetOptimization' => true,
        'enableLazyLoading' => true,
        'enableImageOptimization' => true,
    ],
    
    // Настройки разработки
    'development' => [
        'enableDebugToolbar' => YII_ENV_DEV,
        'enableProfiling' => YII_ENV_DEV,
        'logLevel' => YII_ENV_DEV ? 'debug' : 'error',
    ],
    
    // Настройки локализации
    'localization' => [
        'defaultLanguage' => 'ru',
        'availableLanguages' => ['ru', 'en'],
        'defaultCurrency' => 'RUB',
        'availableCurrencies' => ['RUB', 'USD', 'EUR'],
        'dateFormat' => 'php:d.m.Y',
        'datetimeFormat' => 'php:d.m.Y H:i',
        'timeFormat' => 'php:H:i',
    ],
    
    // Настройки безопасности контента
    'security' => [
        'allowedImageDomains' => [
            'localhost',
            'limaron.ru',
            'cdn.limaron.ru',
        ],
        'contentSecurityPolicy' => [
            'enabled' => false,
            'reportOnly' => true,
        ],
        'csrf' => [
            'enabled' => true,
            'cookieValidationKey' => '', // Должен быть установлен в конфигурации
        ],
    ],
    
    // Настройки мобильного приложения (если планируется)
    'mobile' => [
        'apiEnabled' => false,
        'apiVersion' => 'v1',
        'pushNotifications' => false,
    ],
    
    // Настройки резервного копирования
    'backup' => [
        'enabled' => false,
        'schedule' => '0 2 * * *', // Каждый день в 2:00
        'retention' => 30, // Хранить 30 дней
        'includePaths' => [
            '@app/web/uploads',
            '@app/config',
        ],
    ],
    
    // Настройки мониторинга
    'monitoring' => [
        'enabled' => false,
        'errorTracking' => false,
        'performanceMonitoring' => false,
        'uptimeMonitoring' => false,
    ],
    
    // Лимиты и ограничения
    'limits' => [
        'maxLoginAttempts' => 5,
        'maxRegistrationsPerHour' => 10,
        'maxOrdersPerDay' => 50,
        'maxReviewsPerDay' => 5,
        'maxSearchQueriesPerMinute' => 20,
    ],
    
    // Настройки темизации
    'theme' => [
        'name' => 'default',
        'darkModeEnabled' => true,
        'customCss' => '',
        'customJs' => '',
    ],
    
    // Настройки поисковой оптимизации
    'seo' => [
        'enableSitemap' => true,
        'enableRobotsTxt' => true,
        'enableStructuredData' => true,
        'enableOpenGraph' => true,
        'enableTwitterCards' => true,
    ],
];