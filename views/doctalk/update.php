<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Doctalk */

$this->title = 'Update Doctalk: ' . ' ' . $model->dtlk_id;
$this->params['breadcrumbs'][] = ['label' => 'Doctalks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->dtlk_id, 'url' => ['view', 'id' => $model->dtlk_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="doctalk-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
