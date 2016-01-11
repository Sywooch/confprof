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
//        ['label' => 'About', 'url' => ['/site/about']],
//        ['label' => 'Contact', 'url' => ['/site/contact']],
        ['label' => 'Login', 'url' => ['/site/login']],
    ],
]);

NavBar::end();
