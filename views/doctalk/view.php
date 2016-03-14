<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Doctalk */

$this->title = $model->dtlk_id;
$this->params['breadcrumbs'][] = ['label' => 'Doctalks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doctalk-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->dtlk_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->dtlk_id], [
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
            'dtlk_id',
            'dtlk_us_id',
            'dtlk_doc_id',
            'dtlk_text:ntext',
            'dtlk_created',
        ],
    ]) ?>

</div>
