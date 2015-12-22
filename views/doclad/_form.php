<?php

/**
 * Форма изменения доклада
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\models\Doclad;
use yii\web\JsExpression;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Doclad */
/* @var $form yii\widgets\ActiveForm */

$ekis_id = [
    'language' => 'ru',
    'pluginOptions' => [
        'allowClear' => true,
        'initSelection' => new JsExpression('function (element, callback) {
                    if( element.val() > 0 ) {
                        $.ajax({
                            method: "POST",
                            url: "http://hastur.temocenter.ru/task/eo.search/",
                            dataType: "json",
                            data: {
                                filters: {
                                    eo_id: element.val(),
                                },
                                maskarade: {
                                    eo_id: "id",
                                    eo_short_name: "text"
                                },
                                fields: ["eo_id", "eo_short_name", "eo_district_name_id"].join(";")
                            },
                            success: function (data) {
                                callback(data.list.pop());
                            }
                        });
                    }
                }'),
        'ajax' =>[
            'method' => 'POST',
            'url' => "http://hastur.temocenter.ru/task/eo.search/forhost/ask.educom.ru",
            'dataType' => 'json',
            'withCredentials' => true,
            'data' => new JsExpression('function (params) {
//                        console.log("data("+term+", "+page+")");
                        return {
                            filters: {eo_name: params.term, eo_short_name: params.term},
                            maskarade: {eo_id: "id", eo_short_name: "text", eo_district_name_id: "area_id", eo_subordination_name: "district"},
                            fields: "eo_id;eo_short_name;eo_subordination_name_id;eo_district_name_id",
                            limit: 10,
                            start: (params.page - 1) * 10,
                            "_": (new Date()).getSeconds()
                        };
                    }'),

            'processResults' => new JsExpression('function (data, params) {
                    console.log("processResults() data = ", data, " params = ", params);
                    params.page = params.page || 1;

                    var more = (params.page * 10) < data.total_count; // whether or not there are more results available
                    return {results: data.list, pagination: more};
             }'),
            'id' => new JsExpression('function(item){return item.id;}'),
        ],
        'formatResult' => new JsExpression(
            'function (item) {
                        return  item["text"] + "<span class=\\"description\\">" + item["district"] + "</span>";
                    }'
        ),
        'escapeMarkup' => new JsExpression('function (m) { return m; }'),
    ],

    'pluginEvents' => [
        'change' => 'function(event) {
//                    var sIdReg = "'.Html::getInputId($model, 'msg_pers_region').'";
//                    jQuery("#'.Html::getInputId($model, 'msg_pers_org').'").val(event.added.text);
//                    jQuery("#"+sIdReg).val(event.added.area_id);
                    console.log("change", event);
//                    console.log("set " + sIdReg + " = " + event.added.area_id);
                }',
    ],

    'options' => [
//                    'multiple' => true,
        'placeholder' => 'Выберите учреждение ...',
    ],
];
?>

<div class="doclad-form">

    <?php $form = ActiveForm::begin([
        'id' => 'doclad-form',
        'options' => [
            'class' => 'form-horizontal well'
        ],
        'fieldConfig' => [
//            'template' => "{input}\n{error}",
            'template' => '<div class="control-label">{label}</div><div class="controls">{input}{error}</div>',
            'options' => ['class' => 'control-group'],
//            'labelOptions'=>['class'=>'control-label col-md-6'],
        ],
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
        'validateOnBlur' => false,
        'validateOnChange' => false,
        'validateOnType' => false,
        'validateOnSubmit' => true,
    ]); ?>

    <?= $form->field($model, 'doc_sec_id')->dropDownList($model->aSectionList) // textInput() ?>

    <?= '' // $form->field($model, 'doc_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_subject')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_description')->textarea(['rows' => 6]) ?>

    <?= '' // $form->field($model, 'doc_created')->textInput() ?>

    <?= $form->field($model, 'doc_lider_fam')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_lider_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_lider_otch')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_lider_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_lider_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ekis_id')->widget(Select2::classname(), $ekis_id) // ->textInput() ?>

    <?= $form->field($model, 'doc_lider_org', ['template' => "{input}\n{error}"])->hiddenInput() ?>

    <?php
        if( $model->doc_type == Doclad::DOC_TYPE_PERSONAL ) {
    ?>

    <?= $form->field($model, 'doc_lider_group')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_lider_level')->textInput(['maxlength' => true]) ?>

    <?php
        } else {
    ?>

    <?= $form->field($model, 'doc_lider_position')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_lider_lesson')->textInput(['maxlength' => true]) ?>

    <?php
        }
    ?>

    <div class="controls">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-primary validate']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
