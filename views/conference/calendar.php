<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $conference app\models\Conference */

$this->title = $conference->cnf_title;
// $this->params['breadcrumbs'][] = ['label' => 'Conferences', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

/*
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->cnf_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->cnf_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

*/
?>
<div class="conference-view">

    <?= 'Календарь: ' . date('d.m.Y') ?>

</div>
