<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'us_group')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'us_active')->textInput() ?>

    <?= $form->field($model, 'us_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'us_pass')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'us_created')->textInput() ?>

    <?= $form->field($model, 'us_confirmkey')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'us_key')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
