<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Изменить пользователя';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->us_id, 'url' => ['view', 'id' => $model->us_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-update">

    <!-- h1><?= '' // Html::encode($this->title) ?></h1 -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
