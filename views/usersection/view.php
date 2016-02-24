<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Usersection */

$this->title = $model->usec_id;
$this->params['breadcrumbs'][] = ['label' => 'Usersections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usersection-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->usec_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->usec_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'usec_id',
            'usec_user_id',
            'usec_section_id',
        ],
    ]) ?>

</div>
