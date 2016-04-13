<?php

use yii\helpers\Html;
use yii\grid\GridView;

use app\models\Conference;

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
            [
                'attribute' => 'cnf_title',
                'class' => 'yii\grid\DataColumn',
                'format' => 'raw',
//                'filter' => Conference::getList(),
                'value' => function ($model, $key, $index, $column) {
                    /** @var Conference $model */
                    $sFlag = $model->getFlag();
                    if( !empty($sFlag) ) {
                        $sFlag = '<br /><span style="font-size: 0.8em; color: #999999;">' . Html::encode($sFlag) . '</span>';
                    }
                    return Html::encode($model->cnf_title) . $sFlag;
                }
            ],
//            'cnf_title',
//            'cnf_class',
//            'cnf_controller',
            'cnf_description:ntext',
            // 'cnf_created',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
