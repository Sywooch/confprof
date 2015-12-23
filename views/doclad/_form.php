<?php

/**
 * Форма изменения доклада
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\web\JsExpression;
use kartik\select2\Select2;

use app\models\Doclad;
use app\models\Person;
use app\models\Docmedal;

use mosedu\multirows\MultirowsWidget;

$sCss = <<<EOT
div.required label:after {
    content: " *";
/*    color: red;*/
}
.lio_but {
    white-space: nowrap;
}
EOT;

$this->registerCss($sCss);
$emptyConsultant = new Person();
$emptyConsultant->prs_type = Person::PERSON_TYPE_CONSULTANT;

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
                jQuery("#'.Html::getInputId($model, 'doc_lider_org').'").val(data.text);
            }
        }',
    ],

    'options' => [
//                    'multiple' => true,
        'placeholder' => 'Выберите учреждение ...',
    ],
];
// <div class="doclad-form">
// </div>
?>

    <div class="row">
        <div class="col-xs-12">
            <div class="lio_form_name"><?php echo $model->typeTitle(); ?></div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 ">
            <div class="lio_form_block_inner">

    <?php $form = ActiveForm::begin([
        'id' => 'doclad-form',
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
                        <?= $form->field($model, 'doc_lider_fam')->textInput(['maxlength' => true, 'class'=>'lio_input', 'placeholder'=>$model->getAttributeLabel('doc_lider_fam'), ]) ?>
                    </div>

                    <div class="col-xs-4">
                        <?= $form->field($model, 'doc_lider_name')->textInput(['maxlength' => true, 'class'=>'lio_input', 'placeholder'=>$model->getAttributeLabel('doc_lider_name'), ]) ?>
                    </div>

                    <div class="col-xs-4">
                        <?= $form->field($model, 'doc_lider_otch')->textInput(['maxlength' => true, 'class'=>'lio_input', 'placeholder'=>$model->getAttributeLabel('doc_lider_otch'), ]) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="lio_form_line"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <?= $form
                            ->field($model, 'doc_lider_phone')
                            ->widget(\yii\widgets\MaskedInput::className(), ['mask' => '+7(999)999-99-99', 'options' => ['class'=>'lio_input', 'placeholder'=>$model->getAttributeLabel('doc_lider_phone'),] ]) // ->textInput(['maxlength' => true, 'class'=>'lio_input', 'placeholder'=>$model->getAttributeLabel('doc_lider_phone'), ]) ?>
                    </div>
                    <div class="col-xs-6">
                        <?= $form->field($model, 'doc_lider_email')->textInput(['maxlength' => true, 'class'=>'lio_input', 'placeholder'=>$model->getAttributeLabel('doc_lider_email'), ]) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="lio_form_line"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <?= $form->field($model, 'ekis_id')->widget(Select2::classname(), $ekis_id, ['class'=>'lio_input', 'placeholder'=>$model->getAttributeLabel('ekis_id'), ]) // ->textInput() ?>
                        <?= $form->field($model, 'doc_lider_org', ['template' => "{input}"])->hiddenInput() ?>
                    </div>

                    <div class="col-xs-3">
                        <?php
                        if( $model->doc_type == Doclad::DOC_TYPE_PERSONAL ) {
                        ?>
                            <?= $form->field($model, 'doc_lider_group')->textInput(['maxlength' => true, 'placeholder'=>$model->getAttributeLabel('doc_lider_group'), ]) ?>
                        <?php
                        } else {
                        ?>
                            <?= $form->field($model, 'doc_lider_position')->textInput(['maxlength' => true, 'placeholder'=>$model->getAttributeLabel('doc_lider_position'), ]) ?>
                        <?php
                        }
                        ?>
                    </div>

                    <div class="col-xs-3">
                        <?php
                        if( $model->doc_type == Doclad::DOC_TYPE_PERSONAL ) {
                            ?>
                            <?= $form->field($model, 'doc_lider_level')->textInput(['maxlength' => true, 'placeholder'=>$model->getAttributeLabel('doc_lider_level'), ]) ?>
                        <?php
                        } else {
                            ?>
                            <?= $form->field($model, 'doc_lider_lesson')->textInput(['maxlength' => true, 'placeholder'=>$model->getAttributeLabel('doc_lider_lesson'), ]) ?>
                        <?php
                        }
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="lio_form_WLine"></div>
                    </div>
                </div>

                <?php
                    echo $this->render(
                        'part_consultants',
                        [
                            'form' => $form,
                            'model' => $model,
                            'consultants' => $model->isNewRecord ? [$emptyConsultant] : $model->persons,
                            'ekis_id' => $ekis_id,
                        ]
                    );
                ?>


                <div class="row">
                    <div class="col-xs-12">
                        <div class="lio_block_header">Доклад</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="lio_form_line"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <?= $form->field($model, 'doc_sec_id')->dropDownList($model->aSectionList, ['class' => 'form-control lio_input', 'placeholder'=>$model->getAttributeLabel('doc_sec_id'), ]) // textInput() ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <?= $form->field($model, 'doc_subject')->textInput(['maxlength' => true, 'class' => 'lio_input', 'placeholder'=>$model->getAttributeLabel('doc_subject'), ]) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <?= $form->field($model, 'doc_description')->textarea(['rows' => 5, 'class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('doc_description'), ]) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="lio_form_WLine"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="lio_form_name">Представление работы на конференциях/конкурсах, представленных на сайте mgk.olimpiada.ru</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="add_remove">
                            <a class="lio_add_el add_medal">Добавить</a>
                        </div>
                    </div>
                </div>

                <?php
                echo MultirowsWidget::widget(
                    [
                        'model' => Docmedal::className(),
                        'form' => $form,
//                        'viewparam' => ['ekis_id' => $ekis_id],
                        'records' => $model->medals,
//                        'scenario' => 'createconsultant',
                        'rowview' => '@app/views/docmedal/_form_indoclad.php',
//                        'tagOptions' => ['class' => 'userdata-row'],
//                        'defaultattributes' => ['prs_type' => Person::PERSON_TYPE_CONSULTANT, ],
                        'addlinkselector' => '.add_medal',
                        'dellinkselector' => '.del_medal',
//                        'beforeInsert' => 'function(ob){var obOrg = ob.find(\'[name$="[ekis_id]"]\'); obOrg.removeData();}',
//                        'afterInsert' => 'function(ob){}',
                        'afterDelete' => 'function(){ }',
                        'canDeleteLastRow' => true,
//                        'script' => $sScript,
                    ]
                );
                ?>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="lio_form_line"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-3 col-xs-offset-9">
                        <?= Html::submitButton($model->isNewRecord ? 'Подать заявку' : 'Сохранить', ['class' => 'lio_but']) ?>
                    </div>
                </div>

    <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>


<?php
/*
<div class="row">
    <div class="col-xs-12">

        <div class="row"> <!--Начало - Заголовок формы-->
            <div class="col-xs-12">
                <h3 class="lio_form_header">Участник</h3>
            </div>
        </div><!-- Конец - Заголовок формы-->

        <div class="row"><!--Начало - Блока формы-->
            <div class="col-xs-12 lio_form_block">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="lio_form_name">Обучающийся</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 ">
                        <div class="lio_form_block_inner">
                            <form class="lio_f_val" action="" method="post" enctype="multipart/form-data">

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
                                        <div class="form-group">
                                            <label>Фамилия<span class="red_star">*</span></label>
                                            <input  value="" type="text" name="f" placeholder="Фамилия" class="lio_input validation">
                                        </div>
                                    </div>

                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label>Имя<span class="red_star">*</span></label>
                                            <input  value="" type="text" name="i" placeholder="Имя" class="lio_input validation">
                                        </div>
                                    </div>

                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label>Отчество<span class="red_star">*</span></label>
                                            <input  value="" type="text" name="o" placeholder="Отчество" class="lio_input validation">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="lio_form_line"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Телефон</label>
                                            <input  value="" type="text" name="phone"  class="lio_input" data-inputmask="'mask': '+7(999)999-99-99'">
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Электронный адрес<span class="red_star">*</span></label>
                                            <input  value="" type="email" name="email" placeholder="email" class="lio_input validation">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="lio_form_line"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Образовательная организация<span class="red_star">*</span></label>
                                            <select name="organ" class="form-control lio_input validation">
                                                <option value="">Выберите</option>
                                                <option value="1">2</option>
                                                <option value="2">3</option>
                                                <option value="3">4</option>
                                                <option value="4">5</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label>Класс<span class="red_star">*</span></label>
                                            <select name="klass" class="form-control lio_input validation">
                                                <option value="">Выберите</option>
                                                <option value="1">нет</option>
                                                <option value="2">3</option>
                                                <option value="3">4</option>
                                                <option value="4">5</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label>Курс<span class="red_star">*</span></label>
                                            <select name="kurs" class="form-control lio_input validation">
                                                <option value="">Выберите</option>
                                                <option value="1">нет</option>
                                                <option value="2">3</option>
                                                <option value="3">4</option>
                                                <option value="4">5</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="lio_form_WLine"></div>
                                    </div>
                                </div>
------------------------------------------------------------------------------------------
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="lio_block_header">Научный руководитель</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="lio_form_line"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label>Фамилия<span class="red_star">*</span></label>
                                            <input  value="" type="text" name="ruc_f_1" placeholder="Фамилия" class="lio_input validation">
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label>Имя<span class="red_star">*</span></label>
                                            <input  value="" type="text" name="ruc_f_i" placeholder="Имя" class="lio_input validation">
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label>Отчество<span class="red_star">*</span></label>
                                            <input  value="" type="text" name="ruc_f_o" placeholder="Отчество" class="lio_input validation">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="lio_form_line"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Должность<span class="red_star">*</span></label>
                                            <input  value="" type="text" name="ruc_dolj_1"  class="lio_input validation">
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Электронный адрес<span class="red_star">*</span></label>
                                            <input  value="" type="email" name="ruc_email_1" placeholder="email" class="lio_input validation">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="lio_form_line"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Образовательная организация<span class="red_star">*</span></label>
                                            <select name="ruc_organ_1" class="form-control lio_input validation">
                                                <option value="0">Выберите</option>
                                                <option value="1">2</option>
                                                <option value="2">3</option>
                                                <option value="3">4</option>
                                                <option value="4">5</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Предмет/предметная область<span class="red_star">*</span></label>
                                            <input  value="" type="text" name="ruc_predmet_1" placeholder="" class="lio_input validation">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="add_remove">
                                            <a class="lio_add_el">Добавить</a>
                                            <a class="lio_add_el">Удалить</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="lio_form_WLine"></div>
                                    </div>
                                </div>

------------------------------------------------------------------------------------------

                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="lio_block_header">Научный руководитель</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="lio_form_line"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Секция<span class="red_star">*</span></label>
                                            <select name="section" class="form-control lio_input validation">
                                                <option value="0">Выберите</option>
                                                <option value="1">2</option>
                                                <option value="2">3</option>
                                                <option value="3">4</option>
                                                <option value="4">5</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label>Тема<span class="red_star">*</span></label>
                                            <input  value="" type="text" name="tema" placeholder="" class="lio_input validation">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label>Описание работы<span class="red_star">*</span></label>
                                            <textarea class="form-control validation" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="lio_form_line"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-3 col-xs-offset-9">
                                        <button type="submit" class="lio_but" name="make" value="save">Подать заявку</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- Конец - Блока формы-->
    </div>
</div>

*/