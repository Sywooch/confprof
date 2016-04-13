<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Conference */

$this->title = 'Регистрация окончена';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conference-create">

    <!-- h1><?= Html::encode($this->title) ?></h1 -->

    <p style="text-align: center; font-size: 1.4em; line-height: 1.7em; margin: 40px 0;">
        Регистрация на конференцию <strong><?= Html::encode($model->cnf_title) ?></strong> завершена.
    </p>


</div>
