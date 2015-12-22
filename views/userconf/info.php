<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Conference;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ConferenceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $conference Conference */


$this->title = 'Инфо';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="item-page">
    <div itemprop="articleBody">
        <h3 style="text-align: center;"><?php echo Html::encode($conference->cnf_title); ?></h3>
        <?php echo $conference->cnf_about; ?>
    </div>
</div>
