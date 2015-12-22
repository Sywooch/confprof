<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DocladSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="doclad-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'doc_id') ?>

    <?= $form->field($model, 'doc_sec_id') ?>

    <?= $form->field($model, 'doc_type') ?>

    <?= $form->field($model, 'doc_subject') ?>

    <?= $form->field($model, 'doc_description') ?>

    <?php // echo $form->field($model, 'doc_created') ?>

    <?php // echo $form->field($model, 'doc_lider_fam') ?>

    <?php // echo $form->field($model, 'doc_lider_name') ?>

    <?php // echo $form->field($model, 'doc_lider_otch') ?>

    <?php // echo $form->field($model, 'doc_lider_email') ?>

    <?php // echo $form->field($model, 'doc_lider_phone') ?>

    <?php // echo $form->field($model, 'ekis_id') ?>

    <?php // echo $form->field($model, 'doc_lider_org') ?>

    <?php // echo $form->field($model, 'doc_lider_group') ?>

    <?php // echo $form->field($model, 'doc_lider_level') ?>

    <?php // echo $form->field($model, 'doc_lider_position') ?>

    <?php // echo $form->field($model, 'doc_lider_lesson') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
