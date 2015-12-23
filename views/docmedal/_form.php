<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Docmedal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="docmedal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'mdl_competition')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mdl_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mdl_doc_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
