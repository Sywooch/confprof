<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Docmedal */

$this->title = 'Create Docmedal';
$this->params['breadcrumbs'][] = ['label' => 'Docmedals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="docmedal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
