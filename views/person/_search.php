<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PersonSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="person-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'prs_id') ?>

    <?= $form->field($model, 'prs_active') ?>

    <?= $form->field($model, 'prs_type') ?>

    <?= $form->field($model, 'prs_fam') ?>

    <?= $form->field($model, 'prs_name') ?>

    <?php // echo $form->field($model, 'prs_otch') ?>

    <?php // echo $form->field($model, 'prs_email') ?>

    <?php // echo $form->field($model, 'prs_phone') ?>

    <?php // echo $form->field($model, 'prs_sec_id') ?>

    <?php // echo $form->field($model, 'prs_doc_id') ?>

    <?php // echo $form->field($model, 'ekis_id') ?>

    <?php // echo $form->field($model, 'prs_org') ?>

    <?php // echo $form->field($model, 'prs_group') ?>

    <?php // echo $form->field($model, 'prs_level') ?>

    <?php // echo $form->field($model, 'prs_position') ?>

    <?php // echo $form->field($model, 'prs_lesson') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
