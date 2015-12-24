<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Person */
/* @var $form yii\widgets\ActiveForm */
/* @var $oConference app\models\Conference */

// <div class="person-form">
// </div>

$this->title = 'Регистрация гостя';

?>

<div class="col-xs-12 lio_form_block person-form">
    <div class="row">

        <div class="row">
            <div class="col-xs-12">
                <div class="lio_form_name"><?php  echo Html::encode($this->title); ?></div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 ">
                <div class="lio_form_block_inner">


    <?php $form = ActiveForm::begin([
        'id' => 'guest-form',
        'options' => [
            'class' => 'lio_f_val'
        ],
        'fieldConfig' => [
//            'template' => "{input}\n{error}",
            'template' => '{label}{input}{error}',
            'options' => ['class' => 'form-group'],
//            'labelOptions'=>['class'=>'control-label col-md-6'],
        ],
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
        'validateOnBlur' => false,
        'validateOnChange' => false,
        'validateOnType' => false,
        'validateOnSubmit' => true,
    ]); ?>


                    <div class="row">
                        <div class="col-xs-12">
                            <div class="lio_block_header">Лидер проекта</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="lio_form_line"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-4">
                            <?= $form->field($model, 'prs_fam')->textInput(['maxlength' => true, 'class'=>'lio_input', 'placeholder'=>$model->getAttributeLabel('prs_fam'), ]) ?>
                        </div>

                        <div class="col-xs-4">
                            <?= $form->field($model, 'prs_name')->textInput(['maxlength' => true, 'class'=>'lio_input', 'placeholder'=>$model->getAttributeLabel('prs_name'), ]) ?>
                        </div>

                        <div class="col-xs-4">
                            <?= $form->field($model, 'prs_otch')->textInput(['maxlength' => true, 'class'=>'lio_input', 'placeholder'=>$model->getAttributeLabel('prs_otch'), ]) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="lio_form_line"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6">
                            <?= $form->field($model, 'prs_email')->textInput(['maxlength' => true, 'class'=>'lio_input', 'placeholder'=>$model->getAttributeLabel('prs_email'), ]) ?>
                        </div>
                        <div class="col-xs-6">
                            <?= $form->field($model, 'prs_sec_id')->dropDownList($model->aSectionList, ['class' => 'form-control lio_input', 'placeholder'=>$model->getAttributeLabel('prs_sec_id'), ]) // textInput() ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="lio_form_line"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-4 col-xs-offset-8">
                            <?= Html::submitButton($model->isNewRecord ? 'Зарегистрироваться' : 'Сохранить', ['class' => 'lio_but btn-block']) ?>
                        </div>
                    </div>



                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
</div>
