<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Docmedal */

$this->title = $model->mdl_id;
$this->params['breadcrumbs'][] = ['label' => 'Docmedals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="docmedal-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->mdl_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->mdl_id], [
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
            'mdl_id',
            'mdl_competition',
            'mdl_title',
            'mdl_doc_id',
        ],
    ]) ?>

</div>
