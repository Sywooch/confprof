<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Section */

$this->title = 'Изменение секции: ' . ' ' . $model->sec_title;
$this->params['breadcrumbs'][] = ['label' => 'Секции', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->sec_id, 'url' => ['view', 'id' => $model->sec_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="section-update">

    <!-- h1><?= Html::encode($this->title) ?></h1 -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
