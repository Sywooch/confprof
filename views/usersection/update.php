<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Usersection */

$this->title = 'Update Usersection: ' . ' ' . $model->usec_id;
$this->params['breadcrumbs'][] = ['label' => 'Usersections', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->usec_id, 'url' => ['view', 'id' => $model->usec_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="usersection-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
