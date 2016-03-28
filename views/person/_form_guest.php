<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;

use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Person */
/* @var $form yii\widgets\ActiveForm */
/* @var $oConference app\models\Conference */

// <div class="person-form">
// </div>

$this->title = 'Регистрация гостя';

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
//                    console.log("processResults() data = ", data, " params = ", params);
                    params.page = params.page || 1;

                    var more = (params.page * 10) < data.total_count; // whether or not there are more results available
                    return {results: data.list, pagination: more};
             }'),
            'id' => new JsExpression('function(item){ console.log("id = ", item); return item.id;}'),
        ],
        'formatResult' => new JsExpression(
            'function (item) {
                        return  item["text"] + "<span class=\\"description\\">" + item["district"] + "</span>";
                    }'
        ),
        'escapeMarkup' => new JsExpression('function (m) { return m; }'),
    ],

    'pluginEvents' => [
        'select2:select' => 'function(event) {
//            console.log("select2:select", event);
            if( ("params" in event) && ("data" in event.params) ) {
                var data = event.params.data;
                jQuery("#'.Html::getInputId($model, 'prs_org').'").val(data.text);
            }
        }',
    ],

    'options' => [
//                    'multiple' => true,
        'placeholder' => 'Выберите учреждение ...',
    ],
];

$sCss = <<<EOT
.select2-container--krajee .select2-selection {
    border-color: #bbbbbb;
    border-radius: 0px;
}
EOT;

$this->registerCss($sCss);

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


<!--                    <div class="row">-->
<!--                        <div class="col-xs-12">-->
<!--                            <div class="lio_block_header">Лидер проекта</div>-->
<!--                        </div>-->
<!--                    </div>-->
<!---->
<!--                    <div class="row">-->
<!--                        <div class="col-xs-12">-->
<!--                            <div class="lio_form_line"></div>-->
<!--                        </div>-->
<!--                    </div>-->

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
                            <?= $form->field($model, 'prs_sec_id')->dropDownList($model->aSectionList, ['class' => 'lio_input', 'placeholder'=>$model->getAttributeLabel('prs_sec_id'), ]) // textInput() ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="lio_form_line"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6">
                            <?= $form->field($model, 'ekis_id', ['template' => '{label}{input}{error}{hint}'])->widget(Select2::classname(), $ekis_id, ['class'=>'lio_input', 'placeholder'=>$model->getAttributeLabel('ekis_id'), ])->hint('Для поиска организации введите ее название или номер') // ->textInput() ?>
                            <?= $form->field($model, 'prs_org', ['template' => "{input}"])->hiddenInput() ?>
                        </div>
                        <div class="col-xs-6">
                            <?= $form->field($model, 'prs_position')->textInput($model->aSectionList, ['class' => 'lio_input', 'placeholder'=>$model->getAttributeLabel('prs_position'), ]) ?>
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

<?php echo '<!-- ' . $oConference->cnf_guestlimit . ' .. ' . $oConference->getGuestcount() . ' -->'; ?>