<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\models\Section;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;

$aGroups = User::getAllGroups();

?>
<div class="user-index">

    <!-- h1><?= '' // Html::encode($this->title) ?></h1 -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить пользователя', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            // 'us_id',
//            'us_email:email',
//            'us_group',
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'us_email',
                'format' => 'raw',
                'content' => function ($model, $key, $index, $column) use ($aGroups) {
                    /** @var User $model */
                    return Html::encode($model->us_email) . '<br />' . Html::encode($model->us_name);
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'us_group',
                'filter' => $aGroups,
                'content' => function ($model, $key, $index, $column) use ($aGroups) {
                    /** @var User $model */

                    $sDop = ''; // ( ($model->us_group == User::USER_GROUP_MODERATOR) && ($model->us_mainmoderator == 1) ) ? '<span class="glyphicon glyphicon-star"></span> ' : '';
                    return $sDop . Html::encode(isset($aGroups[$model->us_group]) ? $aGroups[$model->us_group] : '??');
                },
//                'contentOptions' => [
//                    'class' => 'griddate commandcell',
//                ],
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'us_mainmoderator',
                'filter' => [1 => 'Ответственный'],
                'content' => function ($model, $key, $index, $column) use ($aGroups) {
                    /** @var User $model */
                    $sDop = (
                        ($model->us_group == User::USER_GROUP_MODERATOR)
                     && array_reduce(
                            $model->sections,
                            function($carry, $el){ return $carry || $el->usec_section_primary; },
                            false
                        )
                    ) ? '<span class="glyphicon glyphicon-star"></span>' : '';
                    return $sDop;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'sectionids',
                'filter' => Section::getSectionList() ,
                'content' => function ($model, $key, $index, $column) {
                    /** @var User $model */
                    return implode('<br />', ArrayHelper::map(
                        $model->sections,
                        'usec_id',
                        function($el) {
                            return Html::encode($el->section->sec_title) . ($el->usec_section_primary ? ' <span class="glyphicon glyphicon-star"></span>' : '');
                        }
                    ));
                },
//                'contentOptions' => [
//                    'class' => 'griddate commandcell',
//                ],
            ],


            // 'us_active',
            // 'us_pass',
            // 'us_created',
            // 'us_confirmkey',
            // 'us_key',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
