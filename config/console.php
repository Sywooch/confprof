<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

use yii\helpers\ArrayHelper;

$sfCommon = __DIR__ . DIRECTORY_SEPARATOR . 'common.php';
$sfCommonLocal = __DIR__ . DIRECTORY_SEPARATOR . 'common-local.php';
$sfConLocal = __DIR__ . DIRECTORY_SEPARATOR . 'console-local.php';

$config = [
    'id' => 'basic-console',
    'controllerNamespace' => 'app\commands',
    'controllerMap' => [
        'migrate' => [
            'class' => \yii\console\controllers\MigrateController::className(),
            'templateFile' => '@app/views/migration.php',
        ],
    ],
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                    'logFile' => '@app/runtime/logs/console.log',
                    'maxFileSize' => 300,
                    'maxLogFiles' => 3,
                ],
            ],
        ],
    ],
];

$config = ArrayHelper::merge(
    require($sfCommon),
    file_exists($sfCommonLocal) ? require($sfCommonLocal) : [],
    $config,
    file_exists($sfConLocal) ? require($sfConLocal) : []
);
//print_r($config);
//die();
return $config;
/*

return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
    ],
    'params' => $params,
];

*/