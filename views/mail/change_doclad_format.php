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
use app\models\Doclad;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $data app\models\Doclad */

$aLink = ['cabinet/update', 'id' => $data->doc_id];
$sLink = str_replace('/admin/', '/', Url::to($aLink, true));
?>

<p>Здравствуйте.</p>

<p>Модератор изменил формат представления Вашего доклада на сайте <?= Yii::$app->name ?>.</p>

<p>Доклад <?= Html::encode($data->doc_subject) ?>.</p>

<p>Формат представления: <?= Html::encode($data->getFormat()) ?>.</p>

