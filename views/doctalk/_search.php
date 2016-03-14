<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DoctalkSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="doctalk-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'dtlk_id') ?>

    <?= $form->field($model, 'dtlk_us_id') ?>

    <?= $form->field($model, 'dtlk_doc_id') ?>

    <?= $form->field($model, 'dtlk_text') ?>

    <?= $form->field($model, 'dtlk_created') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
