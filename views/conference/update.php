<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Conference */

$this->title = 'Изменение конференции: ' . ' ' . $model->cnf_title;
$this->params['breadcrumbs'][] = ['label' => 'Конференции', 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->cnf_id, 'url' => ['view', 'id' => $model->cnf_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="conference-update">

    <!-- h1><?= Html::encode($this->title) ?></h1 -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
