<?php
/**
 * Created by PhpStorm.
 * User: Vik
 * Date: 09.11.2015
 * Time: 0:17
 */

//use yii;
use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\models\User */

$aLink = ['user/confirmemail', 'key' => $model->us_confirmkey];

?>

<p>Здравствуйте.</p>

<p>Вы зарегистрировались на сайте <?= Yii::$app->name ?>.</p>

<p>Для подтверждения email перейдите по ссылке: <?= Html::a(Url::to($aLink, true), Url::to($aLink, true)) ?></p>

