<?php

use mosedu\multirows\MultirowsWidget;

use app\models\Person;
use app\models\Member;
use yii\helpers\Html;
use yii\web\JsExpression;

/* @var $persontype integer */
/* @var $modelname string */
/* @var $parttitle string */
/* @var $persons array of app\models\Person */
/* @var $this yii\web\View */
/* @var $model app\models\Doclad */
/* @var $form yii\widgets\ActiveForm */

$sLinkNamePart = (($persontype == Person::PERSON_TYPE_STUD_MEMBER) || ($persontype == Person::PERSON_TYPE_ORG_MEMBER)) ? 'member' : 'consultant';

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
                var data = event.params.data,
                    oRow = jQuery(this).closest(".row"),
                    oOrg = oRow.find(\'[name$="[prs_org]"]\');
//                    console.log("oRow = ", oRow, " oOrg = ", oOrg);
                oOrg.val(data.text);
            }
        }',
    ],

    'options' => [
//                    'multiple' => true,
        'placeholder' => 'Выберите учреждение ...',
    ],
];

$s1 = <<<EOT
{"allowClear":true,"initSelection":function (element, callback) {
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
                },"ajax":{"method":"POST","url":"http:\/\/hastur.temocenter.ru\/task\/eo.search\/forhost\/ask.educom.ru","dataType":"json","withCredentials":true,"data":function (params) {
//                        console.log("data("+term+", "+page+")");
                        return {
                            filters: {eo_name: params.term, eo_short_name: params.term},
                            maskarade: {eo_id: "id", eo_short_name: "text", eo_district_name_id: "area_id", eo_subordination_name: "district"},
                            fields: "eo_id;eo_short_name;eo_subordination_name_id;eo_district_name_id",
                            limit: 10,
                            start: (params.page - 1) * 10,
                            "_": (new Date()).getSeconds()
                        };
                    },"processResults":function (data, params) {
//                    console.log("processResults() data = ", data, " params = ", params);
                    params.page = params.page || 1;

                    var more = (params.page * 10) < data.total_count; // whether or not there are more results available
                    return {results: data.list, pagination: more};
             },"id":function(item){ console.log("id = ", item); return item.id;}},"formatResult":function (item) {
                        return  item["text"] + "<span class=\"description\">" + item["district"] + "</span>";
                    },"escapeMarkup":function (m) { return m; },"theme":"krajee","width":"100%","placeholder":"Выберите учреждение ...","language":"ru"}
EOT;

// var paramSelect2EkisId = {$sSelect2Param};
$sId0Cons = Html::getInputId(count($persons) ? $persons[0] : new $modelname, '[0]ekis_id');
$sConfigName = 'paramSel2Ekis' . $sLinkNamePart . substr(md5(Yii::$app->security->generateRandomKey(16)), 0, 6);

$sScript = <<<EOT
var {$sConfigName} = {$s1};
jQuery('#{$sId0Cons}').select2("destroy");

EOT;


?>

<div class="row">
    <div class="col-xs-12">
        <div class="lio_block_header"><?php echo Html::encode($parttitle); ?> <?php
            if( ($persontype == Person::PERSON_TYPE_STUD_MEMBER) || ($persontype == Person::PERSON_TYPE_ORG_MEMBER) ) {
            ?>
                <a class="lio_add_el add_member">Добавить</a>
            <?php

            } ?></div>
    </div>
</div>

<?php
//if( false )
echo MultirowsWidget::widget(
    [
        'model' => (new $modelname)->className(),
        'form' => $form,
        'viewparam' => ['ekis_id' => $ekis_id, 'linkname' => $sLinkNamePart, ],
        'records' => $persons,
        'scenario' => 'create' . $sLinkNamePart,
        'rowview' => '@app/views/person/_form_indoclad.php',
        'tagOptions' => ['class' => 'userdata-row'],
        'defaultattributes' => ['prs_type' => $persontype, ],
        'addlinkselector' => '.add_' . $sLinkNamePart,
        'dellinkselector' => '.del_' . $sLinkNamePart,
        'beforeInsert' => 'function(ob){var obOrg = ob.find(\'[name$="[ekis_id]"]\'); obOrg.removeData();}',
        'afterInsert' => 'function(ob){
                var obEkis = ob.find(\'[name$="[ekis_id]"]\'),
                    sId = obEkis.attr("id");

                obEkis.select2('.$sConfigName.');

                obEkis.on("select2:select", function(event) {
                    console.log("select2:select", event);
                    if( ("params" in event) && ("data" in event.params) ) {
                        var data = event.params.data,
                            oRow = jQuery(this).closest(".row"),
                            oOrg = oRow.find(\'[name$="[prs_org]"]\');
//                            console.log("oRow = ", oRow, " oOrg = ", oOrg);
                        oOrg.val(data.text);
                    }
                });
        }',
        'afterDelete' => 'function(){ console.log("Delete row : resource"); }',
        'canDeleteLastRow' => true,
        'script' => $sScript,
    ]
);
?>

<div class="row">
    <div class="col-xs-12">
        <div class="lio_form_WLine"></div>
    </div>
</div>

