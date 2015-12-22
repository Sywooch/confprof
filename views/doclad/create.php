<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Doclad */

$this->title = 'Create Doclad';
$this->params['breadcrumbs'][] = ['label' => 'Doclads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doclad-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
