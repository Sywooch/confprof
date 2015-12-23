<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PersonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'People';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="person-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Person', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'prs_id',
            'prs_active',
            'prs_type',
            'prs_fam',
            'prs_name',
            // 'prs_otch',
            // 'prs_email:email',
            // 'prs_phone',
            // 'prs_sec_id',
            // 'prs_doc_id',
            // 'ekis_id',
            // 'prs_org',
            // 'prs_group',
            // 'prs_level',
            // 'prs_position',
            // 'prs_lesson',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
