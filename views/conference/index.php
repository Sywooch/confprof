<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ConferenceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Конференции';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conference-index">

    <!-- h1><?= Html::encode($this->title) ?></h1 -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать конференцию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

//            'cnf_id',
            'cnf_title',
//            'cnf_class',
//            'cnf_controller',
            'cnf_description:ntext',
            // 'cnf_created',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
