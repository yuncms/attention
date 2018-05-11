<?php
return [
    'id' => 'attention',
    'migrationPath' => '@vendor/yuncms/attention/migrations',
    'translations' => [
        'yuncms/attention' => [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@vendor/yuncms/attention/messages',
        ],
    ],
    'frontend' => [
        'class' => 'yuncms\attention\frontend\Module',
    ],
];