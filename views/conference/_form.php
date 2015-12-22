<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Conference */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="conference-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cnf_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cnf_class')->textInput(['maxlength' => true]) ?>

    <?= '' // $form->field($model, 'cnf_controller')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cnf_description')->textarea(['rows' => 2]) ?>

    <?= $form->field($model, 'cnf_pagetitle')->textarea(['rows' => 2]) ?>

    <?= $form->field($model, 'cnf_about')->textarea(['rows' => 10]) ?>

    <?= '' // $form->field($model, 'cnf_created')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
