<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Docmedal */

$this->title = 'Update Docmedal: ' . ' ' . $model->mdl_id;
$this->params['breadcrumbs'][] = ['label' => 'Docmedals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->mdl_id, 'url' => ['view', 'id' => $model->mdl_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="docmedal-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
