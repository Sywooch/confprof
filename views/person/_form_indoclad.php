<?php

/*
 * Форма для ввода руководителя в форме доклада
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Person;
use kartik\select2\Select2;

$ekis_id = [];
if( isset($viewparam) && isset($viewparam['ekis_id']) ) {
    $ekis_id = $viewparam['ekis_id'];
}

/* @var $this yii\web\View */
/* @var $model app\models\Person */
/* @var $form yii\widgets\ActiveForm */
/* @var $viewparam array of view pameters */

// Yii::info('Start template: ' . $model->className() . ' prs_type = ' . $model->prs_type);
?>

<div class="row">
    <div class="col-xs-12">
        <div class="lio_form_line"></div>
    </div>
</div>

<?= $form->field($model, '[' . $index . ']prs_type', ['template' => "{input}"])->hiddenInput() ?>
<div class="row">
    <div class="col-xs-4">
        <?= $form->field($model, '[' . $index . ']prs_fam')->textInput(['maxlength' => true, 'class'=>'form-control lio_input', 'placeholder'=>$model->getAttributeLabel('prs_fam'),]) ?>
    </div>
    <div class="col-xs-4">
        <?= $form->field($model, '[' . $index . ']prs_name')->textInput(['maxlength' => true, 'class'=>'form-control lio_input', 'placeholder'=>$model->getAttributeLabel('prs_name'),]) ?>
    </div>
    <div class="col-xs-4">
        <?= $form->field($model, '[' . $index . ']prs_otch')->textInput(['maxlength' => true, 'class'=>'form-control lio_input', 'placeholder'=>$model->getAttributeLabel('prs_otch'),]) ?>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="lio_form_line"></div>
    </div>
</div>

<?php if( $model->prs_type == Person::PERSON_TYPE_CONSULTANT ) { ?>
<div class="row">
    <div class="col-xs-6">
        <?= $form->field($model, '[' . $index . ']prs_position')->textInput(['maxlength' => true, 'class'=>'form-control lio_input', 'placeholder'=>$model->getAttributeLabel('prs_position'),]) ?>
    </div>
    <div class="col-xs-6">
        <?= $form->field($model, '[' . $index . ']prs_email')->textInput(['maxlength' => true, 'class'=>'form-control lio_input', 'placeholder'=>$model->getAttributeLabel('prs_email'),]) ?>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="lio_form_line"></div>
    </div>
</div>
<?php } ?>

<div class="row">
    <div class="col-xs-6">
        <?= $form->field($model, '[' . $index . ']ekis_id')->widget(Select2::classname(), $ekis_id, ['class'=>'form-control lio_input', 'placeholder'=>$model->getAttributeLabel('ekis_id'), ]) // ->textInput(['class' => 'ajax_consultant']) ?>
        <?= $form->field($model, '[' . $index . ']prs_org', ['template' => "{input}"])->hiddenInput() ?>
    </div>
    <?php if( $model->prs_type == Person::PERSON_TYPE_CONSULTANT ) { ?>
    <div class="col-xs-6">
        <?= $form->field($model, '[' . $index . ']prs_lesson')->textInput(['maxlength' => true, 'class'=>'form-control lio_input', 'placeholder'=>$model->getAttributeLabel('prs_lesson'),]) ?>
    </div>
    <?php } ?>

    <?php if( $model->prs_type == Person::PERSON_TYPE_STUD_MEMBER ) { ?>
        <div class="col-xs-3">
            <?= $form->field($model, '[' . $index . ']prs_group')->textInput(['maxlength' => true, 'class'=>'form-control lio_input', 'placeholder'=>$model->getAttributeLabel('prs_group'),]) ?>
        </div>
        <div class="col-xs-3">
            <?= '' // $form->field($model, '[' . $index . ']prs_level')->textInput(['maxlength' => true, 'class'=>'form-control lio_input', 'placeholder'=>$model->getAttributeLabel('prs_level'),]) ?>
        </div>
    <?php } ?>

    <?php if( $model->prs_type == Person::PERSON_TYPE_ORG_MEMBER ) { ?>
        <div class="col-xs-3">
            <?= $form->field($model, '[' . $index . ']prs_position')->textInput(['maxlength' => true, 'class'=>'form-control lio_input', 'placeholder'=>$model->getAttributeLabel('prs_position'),]) ?>
        </div>
        <div class="col-xs-3">
            <?= $form->field($model, '[' . $index . ']prs_lesson')->textInput(['maxlength' => true, 'class'=>'form-control lio_input', 'placeholder'=>$model->getAttributeLabel('prs_lesson'),]) ?>
        </div>
    <?php } ?>
</div>

<div class="row">
    <div class="col-xs-12 mg-t-20">
        <?= $form->field(
            $model,
            '[' . $index . ']prs_agree_pers'
        )
            ->checkbox([
                'placeholder'=>$model->getAttributeLabel('prs_agree_pers'),
                'labelOptions' => ['class' => 'control-label'],
                'style' => 'margin-right: 15px;',
            ]) ?>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="add_remove">
            <?php // echo 'index = ' . $index . ' startindex = ' . $startindex; ?>
            <?php if( $index == ($startindex + 1) ) { ?>
                <?php if( $model->prs_type == Person::PERSON_TYPE_CONSULTANT ) {
                    // у консультантов есть один и в нем уже будет ссылка на добавление
                    // у участников нет изначально ничего и ссылка на добавление в названии раздел (Участники)
                ?>
                    <a class="lio_add_el add_<?php echo $viewparam['linkname']; ?>">Добавить</a>
                <?php }?>
            <?php } else { ?>
            <a class="lio_add_el del_<?php echo $viewparam['linkname']; ?>">Удалить</a>
            <?php } ?>
        </div>
    </div>
</div>

<!-- div style="color: #770000; font-weight: bold; background-color: #ddffcc;">
<?= '' // nl2br(str_replace(' ', '&nbsp;', print_r($form->attributes, true)))  ?>
</div -->
<?= '' // $form->field($model, '[' . $index . ']prs_phone')->textInput(['maxlength' => true, 'class'=>'lio_input', 'placeholder'=>$model->getAttributeLabel('prs_phone'),]) ?>



