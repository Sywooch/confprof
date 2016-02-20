<?php

use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use app\models\User;

NavBar::begin([
    'brandLabel' => 'Конференции',
    'brandUrl' => '/',
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);

$items = [
    ['label' => 'Главная', 'url' => ['/site/index']],
];

if( Yii::$app->user->can(User::USER_GROUP_MODERATOR) ) {
    $items = array_merge(
        $items,
        [
            ['label' => 'Доклады', 'url' => ['report/index']],
            ['label' => 'Гости', 'url' => ['guest/index']],
            ['label' => 'Секции', 'url' => ['section/index']],
        ]
    );
}

Yii::info('Menu test: ' . User::USER_GROUP_ADMIN);
if( Yii::$app->user->can(User::USER_GROUP_ADMIN) ) {
    $items = array_merge(
        $items,
        [
            ['label' => 'Пользователи', 'url' => ['user/index']],
        ]
    );
}

if( Yii::$app->user->isGuest ) {
    $items[] = ['label' => 'Вход', 'url' => ['/site/login']];
}
else {
    $items[] = [
        'label' => 'Выход', //  (' . Yii::$app->user->identity->username . ')
        'url' => ['site/logout'],
        'linkOptions' => ['data-method' => 'post']
    ];
}



echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $items,
]);

NavBar::end();
