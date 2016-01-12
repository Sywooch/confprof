<?php

use yii\helpers\Html;
use yii\grid\GridView;

use app\models\File;
use app\models\Conference;
use app\models\Person;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DocladSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Гости';
$this->params['breadcrumbs'][] = $this->title;

/* <h1><?= Html::encode($this->title) ?></h1> */

?>
<div class="doclad-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!-- p>
        <?= '' // Html::a('Create Doclad', ['create'], ['class' => 'btn btn-success']) ?>
    </p -->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'conferenceid',
                'filter' => Conference::getList(),
                'format' => 'raw',
                'value' => function ($model, $key, $index, $column) {
                    /** @var $model app\models\Person */
                    return $model->section !== null ?
                        (Html::encode($model->section->conference->cnf_title) . '<br />' . Html::encode($model->section->sec_title)):
                        '';
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'prs_fam',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $column) {
                    /** @var $model app\models\Person */
                    return Html::encode($model->getPersonname(false) )
                    . ' '
                    . Yii::$app->formatter->asEmail($model->prs_email)
                    . '<br />'
                    . Html::encode($model->prs_org);
                },
            ],
//            'doc_subject',
//            'doc_description:ntext',
//            'doc_created',
            // 'doc_lider_fam',
            // 'doc_lider_name',
            // 'doc_lider_otch',
            // 'doc_lider_email:email',
            // 'doc_lider_phone',
            // 'ekis_id',
            // 'doc_lider_org',
            // 'doc_lider_group',
            // 'doc_lider_level',
            // 'doc_lider_position',
            // 'doc_lider_lesson',

//            [
//                'class' => 'yii\grid\ActionColumn',
//                'template' => '{view}', // {delete}  {update}
//            ],
        ],
    ]); ?>

</div>

