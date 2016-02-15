<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Person */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="person-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'prs_active')->textInput() ?>

    <?= $form->field($model, 'prs_type')->textInput() ?>

    <?= $form->field($model, 'prs_fam')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'prs_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'prs_otch')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'prs_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'prs_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'prs_sec_id')->textInput() ?>

    <?= $form->field($model, 'prs_doc_id')->textInput() ?>

    <?= $form->field($model, 'ekis_id')->textInput() ?>

    <?= $form->field($model, 'prs_org')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'prs_group')->textInput(['maxlength' => true]) ?>

    <?= '' // $form->field($model, 'prs_level')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'prs_position')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'prs_lesson')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
