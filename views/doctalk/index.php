<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DoctalkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Doctalks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doctalk-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Doctalk', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'dtlk_id',
            'dtlk_us_id',
            'dtlk_doc_id',
            'dtlk_text:ntext',
            'dtlk_created',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
