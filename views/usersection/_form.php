<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Usersection */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="usersection-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'usec_user_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'usec_section_id')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
