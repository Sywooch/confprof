<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Conference;

/* @var $this yii\web\View */
/* @var $model app\models\Section */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="section-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sec_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sec_cnf_id')->dropDownList(Conference::getList()) // textInput(['maxlength' => true]) ?>

    <?= '' // $form->field($model, 'sec_created')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
