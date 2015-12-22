<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'us_id') ?>

    <?= $form->field($model, 'us_group') ?>

    <?= $form->field($model, 'us_active') ?>

    <?= $form->field($model, 'us_email') ?>

    <?= $form->field($model, 'us_pass') ?>

    <?php // echo $form->field($model, 'us_created') ?>

    <?php // echo $form->field($model, 'us_confirmkey') ?>

    <?php // echo $form->field($model, 'us_key') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
