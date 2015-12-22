<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ConferenceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="conference-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'cnf_id') ?>

    <?= $form->field($model, 'cnf_title') ?>

    <?= $form->field($model, 'cnf_class') ?>

    <?= $form->field($model, 'cnf_controller') ?>

    <?= $form->field($model, 'cnf_description') ?>

    <?php // echo $form->field($model, 'cnf_created') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
