<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\models\Conference;

/* @var $this yii\web\View */
/* @var $model app\models\Conference */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="conference-form">

    <?php $form = ActiveForm::begin([
        'id' => 'conference-form',
//        'fieldConfig' => [
//            'template' => '{label}{input}{error}{hint}',
//            'options' => ['class' => 'form-group'],
////            'labelOptions'=>['class'=>'control-label col-md-6'],
//        ],
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
        'validateOnBlur' => false,
        'validateOnChange' => false,
        'validateOnType' => false,
        'validateOnSubmit' => true,
    ]); ?>

    <div class="row">
        <div class="col-xs-6">
        <?= $form->field($model, 'cnf_title')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-xs-6">
        <?= $form->field($model, 'cnf_shorttitle')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-3">
            <?= $form->field($model, 'cnf_class')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-xs-3">
            <?= $form->field($model, 'cnf_flags')->dropDownList(Conference::getAllFlags()) ?>
        </div>

        <div class="col-xs-3">
            <?= $form->field($model, 'cnf_controller')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-xs-3">
            <?= $form->field($model, 'cnf_guestlimit')->textInput(['maxlength' => true]) ?>
        </div>
    </div>



    <?= $form->field($model, 'cnf_isconshicshool')->checkbox() ?>

    <?= $form->field($model, 'cnf_description')->textarea(['rows' => 2]) ?>

    <?= $form->field($model, 'cnf_pagetitle')->textarea(['rows' => 2]) ?>

    <?= $form->field($model, 'cnf_about')->textarea(['rows' => 10]) ?>

    <?= '' // $form->field($model, 'cnf_created')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
