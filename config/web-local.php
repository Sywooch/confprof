<?php
return [
    'components' => [
        'request' => [
            'cookieValidationKey' => 'QcS_3ko8005NPAkJQeXq5o69jXtrIc1m',
        ],
//        'assetManager' => [
//            'linkAssets' => true,
//        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error'],
                    'logFile' => '@app/runtime/logs/web-error.log',
                    'maxFileSize' => 300,
                    'maxLogFiles' => 3,
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['warning', 'info', 'trace', ],
                    'logFile' => '@app/runtime/logs/web-warning.log',
                    'maxFileSize' => 300,
                    'maxLogFiles' => 3,
                ],
            ],
        ],
    ],
];