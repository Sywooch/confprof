<?php

use yii\helpers\ArrayHelper;

$sfCommon = __DIR__ . DIRECTORY_SEPARATOR . 'common.php';
$sfCommonLocal = __DIR__ . DIRECTORY_SEPARATOR . 'common-local.php';
$sfWebLocal = __DIR__ . DIRECTORY_SEPARATOR . 'web-local.php';

$config = [
    'id' => 'user',
    'language' => 'ru',
    'name' => 'Конференции: администрирование',
    'components' => [
        'user' => [
            'identityClass' => 'app\models\Adminuser',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'request' => [
            'cookieValidationKey' => '',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

$config = ArrayHelper::merge(
    require($sfCommon),
    file_exists($sfCommonLocal) ? require($sfCommonLocal) : [],
    $config,
    file_exists($sfWebLocal) ? require($sfWebLocal) : []
);

return $config;
