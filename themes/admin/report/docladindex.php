<?php

use yii\helpers\Html;
use yii\grid\GridView;

use app\models\File;
use app\models\Conference;
use app\models\Doclad;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DocladSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Доклады';
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
//            ['class' => 'yii\grid\SerialColumn'],
//            'doc_id',
//            'doc_sec_id',
//            'doc_type',
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'conferenceid',
                'filter' => Conference::getList(),
                'format' => 'raw',
                'value' => function ($model, $key, $index, $column) {
                    /** @var $model app\models\Doclad */
                    return $model->section !== null ?
                        (Html::encode($model->section->conference->cnf_title) . '<br />' . Html::encode($model->section->sec_title)):
                        '';
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'doc_subject',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $column) {
                    $sFiles = '';
                    $aFiles = $model->files;
                    if( count($aFiles) > 0 ) {
                        $sFiles = array_reduce(
                            $aFiles,
                            function($sRes, $item){
                                /** @var File $item */
                                return '<br />' . Html::a($item->file_orig_name, str_replace(DIRECTORY_SEPARATOR, '/', $item->file_name)) . $sRes;
                            },
                            ''
                        );
                    }
                    /** @var $model app\models\Doclad */
                    return Html::encode($model->doc_subject)
                    . ($sFiles != '' ? '<br />' : '' )
                    . $sFiles
                        ;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'doc_type',
                'filter' => Doclad::getAllTypes(),
                'format' => 'raw',
                'value' => function ($model, $key, $index, $column) {
                    /** @var $model app\models\Doclad */
                    return Html::encode($model->typeTitle());
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'doc_lider_fam',
//                'filter' => Conference::getList(),
                'format' => 'raw',
                'value' => function ($model, $key, $index, $column) {
                    /** @var $model app\models\Doclad */
                    return Html::encode($model->getLeadername(false))
                        . '<br />'
                        . Yii::$app->formatter->asEmail($model->doc_lider_email)
                        . ' '
                        . Html::encode($model->doc_lider_phone);
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

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}', // {delete}  {update}
            ],
        ],
    ]); ?>

</div>

