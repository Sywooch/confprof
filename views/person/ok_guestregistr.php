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
                            <p>Ваша почта проверена и Вы зарегистрированы в качестве гостя.</p>
                        </div>
                    </div>

    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
</div>
