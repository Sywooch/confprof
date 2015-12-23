<?php

/*
 * Форма для ввода руководителя в форме доклада
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

$ekis_id = [];
if( isset($viewparam) && isset($viewparam['ekis_id']) ) {
    $ekis_id = $viewparam['ekis_id'];
}

/* @var $this yii\web\View */
/* @var $model app\models\Person */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="row">
    <div class="col-xs-12">
        <div class="lio_form_line"></div>
    </div>
</div>

<?= $form->field($model, '[' . $index . ']prs_type', ['template' => "{input}"])->hiddenInput() ?>
<div class="row">
    <div class="col-xs-4">
        <?= $form->field($model, '[' . $index . ']prs_fam')->textInput(['maxlength' => true, 'class'=>'lio_input', 'placeholder'=>$model->getAttributeLabel('prs_fam'),]) ?>
    </div>
    <div class="col-xs-4">
        <?= $form->field($model, '[' . $index . ']prs_name')->textInput(['maxlength' => true, 'class'=>'lio_input', 'placeholder'=>$model->getAttributeLabel('prs_name'),]) ?>
    </div>
    <div class="col-xs-4">
        <?= $form->field($model, '[' . $index . ']prs_otch')->textInput(['maxlength' => true, 'class'=>'lio_input', 'placeholder'=>$model->getAttributeLabel('prs_otch'),]) ?>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="lio_form_line"></div>
    </div>
</div>

<div class="row">
    <div class="col-xs-6">
        <?= $form->field($model, '[' . $index . ']prs_position')->textInput(['maxlength' => true, 'class'=>'lio_input', 'placeholder'=>$model->getAttributeLabel('prs_position'),]) ?>
    </div>
    <div class="col-xs-6">
        <?= $form->field($model, '[' . $index . ']prs_email')->textInput(['maxlength' => true, 'class'=>'lio_input', 'placeholder'=>$model->getAttributeLabel('prs_email'),]) ?>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="lio_form_line"></div>
    </div>
</div>

<div class="row">
    <div class="col-xs-6">
        <?= $form->field($model, '[' . $index . ']ekis_id')->widget(Select2::classname(), $ekis_id, ['class'=>'lio_input', 'placeholder'=>$model->getAttributeLabel('ekis_id'), ]) // ->textInput(['class' => 'ajax_consultant']) ?>
        <?= $form->field($model, '[' . $index . ']prs_org', ['template' => "{input}"])->hiddenInput() ?>
    </div>
    <div class="col-xs-6">
        <?= $form->field($model, '[' . $index . ']prs_lesson')->textInput(['maxlength' => true, 'class'=>'lio_input', 'placeholder'=>$model->getAttributeLabel('prs_lesson'),]) ?>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="add_remove">
            <?php if( $index == 1 ) { ?>
            <a class="lio_add_el add_consultant">Добавить</a>
            <?php } else { ?>
            <a class="lio_add_el del_consultant">Удалить</a>
            <?php } ?>
        </div>
    </div>
</div>

<?= '' // $form->field($model, '[' . $index . ']prs_phone')->textInput(['maxlength' => true, 'class'=>'lio_input', 'placeholder'=>$model->getAttributeLabel('prs_phone'),]) ?>


