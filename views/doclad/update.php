<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Doclad */

$this->title = 'Update Doclad: ' . ' ' . $model->doc_id;
$this->params['breadcrumbs'][] = ['label' => 'Doclads', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->doc_id, 'url' => ['view', 'id' => $model->doc_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="doclad-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
