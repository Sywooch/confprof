<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Conference;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Секции';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="section-index">

    <!-- h1><?= Html::encode($this->title) ?></h1 -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Section', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

//            'sec_id',
            'sec_title',
            [
                'attribute' => 'sec_cnf_id',
                'class' => 'yii\grid\DataColumn',
                'filter' => Conference::getList(),
                'value' => function ($model, $key, $index, $column) {
                    return Conference::getById($model->sec_cnf_id);
                }
            ],
//            'sec_cnf_id',
//            'sec_created',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
