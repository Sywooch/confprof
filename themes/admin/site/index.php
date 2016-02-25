<?php

use yii\bootstrap\Html;
use app\components\Statistics;

/* @var $this yii\web\View */

$this->title = Yii::$app->name;

echo $this->render(
    '//report/statistics',
    [
        'data' => Statistics::getConferenceStat(),
    ]
);
/*
<p><?= Html::encode($this->title) ?></p>
<p><?= Html::encode(Yii::$app->controller->layout) ?></p>
*/

?>


<!--Остальные страницы-->