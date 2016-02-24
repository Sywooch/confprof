<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Usersections';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usersection-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Usersection', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'usec_id',
            'usec_user_id',
            'usec_section_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
