<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Doclad */

$this->title = $model->doc_id;
$this->params['breadcrumbs'][] = ['label' => 'Doclads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doclad-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->doc_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->doc_id], [
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
            'doc_id',
            'doc_sec_id',
            'doc_type',
            'doc_subject',
            'doc_description:ntext',
            'doc_created',
            'doc_lider_fam',
            'doc_lider_name',
            'doc_lider_otch',
            'doc_lider_email:email',
            'doc_lider_phone',
            'ekis_id',
            'doc_lider_org',
            'doc_lider_group',
            'doc_lider_level',
            'doc_lider_position',
            'doc_lider_lesson',
        ],
    ]) ?>

</div>
