<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Conference */

$this->title = 'Создание конференции';
$this->params['breadcrumbs'][] = ['label' => 'Конференции', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conference-create">

    <!-- h1><?= Html::encode($this->title) ?></h1 -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
