<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Conference;
use app\models\Doclad;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Секции';
$this->params['breadcrumbs'][] = $this->title;

$aDocladTypes = Doclad::getAllTypes();

?>
<div class="section-index">

    <!-- h1><?= Html::encode($this->title) ?></h1 -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить секцию', ['create'], ['class' => 'btn btn-success']) ?>
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
            [
                'attribute' => 'sec_doclad_type',
//                'header' => 'Доклад',
                'class' => 'yii\grid\DataColumn',
                'filter' => $aDocladTypes,
                'value' => function ($model, $key, $index, $column) use ($aDocladTypes) {
                    return isset($aDocladTypes[$model->sec_doclad_type]) ? $aDocladTypes[$model->sec_doclad_type] : '';
                }
            ],
//            'sec_cnf_id',
//            'sec_created',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}', // {view}
            ],
        ],
    ]); ?>

</div>
