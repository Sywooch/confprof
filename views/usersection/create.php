<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Usersection */

$this->title = 'Create Usersection';
$this->params['breadcrumbs'][] = ['label' => 'Usersections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usersection-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
