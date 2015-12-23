<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DocmedalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Docmedals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="docmedal-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Docmedal', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'mdl_id',
            'mdl_competition',
            'mdl_title',
            'mdl_doc_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
