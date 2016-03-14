<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Doctalk */

$this->title = 'Create Doctalk';
$this->params['breadcrumbs'][] = ['label' => 'Doctalks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doctalk-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
