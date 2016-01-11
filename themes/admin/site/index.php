<?php

use yii\bootstrap\Html;

/* @var $this yii\web\View */

$this->title = Yii::$app->name;
?>
    <p><?= Html::encode($this->title) ?></p>
    <p><?= Html::encode(Yii::$app->controller->layout) ?></p>


<!--Остальные страницы-->