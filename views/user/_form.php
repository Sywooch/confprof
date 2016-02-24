<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
        'id' => 'user-form',
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
        <div class="col-xs-3">
            <?= $form->field($model, 'us_active')->checkbox() // textInput() ?>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="row">
        <div class="col-xs-3">
            <?= $form->field($model, 'us_email')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-xs-3">
            <?= $form->field($model, 'password')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-xs-3">
            <?= $form->field($model, 'us_group')->dropDownList(User::getAllGroups()) // textInput(['maxlength' => true]) ?>
        </div>
        <div class="clearfix"></div>
    </div>

    <?php
        if( $model->us_group == User::USER_GROUP_MODERATOR ) {
            echo $this->render(
                'part_sections',
                [
                    'form' => $form,
                    'model' => $model,
                ]
            );

        }

    ?>

    <?= '' // $form->field($model, 'us_created')->textInput() ?>

    <?= '' // $form->field($model, 'us_confirmkey')->textInput(['maxlength' => true]) ?>

    <?= '' // $form->field($model, 'us_key')->textInput(['maxlength' => true]) ?>

    <div class="row">
        <div class="col-xs-3">
            <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
