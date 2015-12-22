<?php
/*
DROP DATABASE IF EXISTS confprof;
CREATE DATABASE avvita;
GRANT ALL PRIVILEGES ON confprof.* TO 'uconfprof'@'%' IDENTIFIED BY 'pconfprof' WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON confprof.* TO 'uconfprof'@'localhost' IDENTIFIED BY 'pconfprof' WITH GRANT OPTION;
*/
return [
    'components' => [
        'db' => [
            'dsn' => 'mysql:host=localhost;dbname=confprof',
            'username' => 'uconfprof',
            'password' => 'pconfprof',
            'tablePrefix' => 'confprof_',
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 24 * 3600,
        ],
        'mailer' => [
            'useFileTransport' => true,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
];
