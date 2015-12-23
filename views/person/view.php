<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Person */

$this->title = $model->prs_id;
$this->params['breadcrumbs'][] = ['label' => 'People', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="person-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->prs_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->prs_id], [
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
            'prs_id',
            'prs_active',
            'prs_type',
            'prs_fam',
            'prs_name',
            'prs_otch',
            'prs_email:email',
            'prs_phone',
            'prs_sec_id',
            'prs_doc_id',
            'ekis_id',
            'prs_org',
            'prs_group',
            'prs_level',
            'prs_position',
            'prs_lesson',
        ],
    ]) ?>

</div>
