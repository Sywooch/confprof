<?php

use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;

NavBar::begin([
    'brandLabel' => 'Конференции',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);

echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => [
        ['label' => 'Главная', 'url' => ['/site/index']],
        [
            'label' => 'Logout', //  (' . Yii::$app->user->identity->username . ')
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post']
        ],
    ],
]);

NavBar::end();
