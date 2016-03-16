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

$aLink = ['user/setnewpassword', 'key' => $model->us_key];

?>

<p>Здравствуйте.</p>

<p>Кто-то запросил воостановление пароля на сайте <?= Yii::$app->name ?>.</p>

<p>Если это были Вы, то перейдите по ссылке: <?= Html::a(Url::to($aLink, true), Url::to($aLink, true)) ?></p>

<p>Если Вы не запрашивали восстановление пароля, то проигнорируйте это письмо.</p>

