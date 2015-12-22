<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Doclad */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="doclad-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'doc_sec_id')->textInput() ?>

    <?= $form->field($model, 'doc_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_subject')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'doc_created')->textInput() ?>

    <?= $form->field($model, 'doc_lider_fam')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_lider_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_lider_otch')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_lider_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_lider_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ekis_id')->textInput() ?>

    <?= $form->field($model, 'doc_lider_org')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_lider_group')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_lider_level')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_lider_position')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_lider_lesson')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
