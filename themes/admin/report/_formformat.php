<?php

/**
 * Изменение статуса доклада
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\models\Doclad;

$sCss = <<<EOT
div.required label:after {
    content: " *";
/*    color: red;*/
}
EOT;

$this->registerCss($sCss);

/* @var $this yii\web\View */
/* @var $model app\models\Doclad */
/* @var $form yii\widgets\ActiveForm */


// <div class="doclad-form">
// </div>
?>

    <?php $form = ActiveForm::begin([
        'id' => 'doclad-form',
        'options' => [
            'class' => 'form-horizontal',
            'enctype' => 'multipart/form-data',
        ],
        'fieldConfig' => [
//            'template' => "{input}\n{error}",
            'template' => '{label}{input}{error}',
            'options' => ['class' => ''],
//            'labelOptions'=>['class'=>'control-label col-md-6'],
        ],
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
        'validateOnBlur' => false,
        'validateOnChange' => false,
        'validateOnType' => false,
        'validateOnSubmit' => true,
    ]); ?>

<?php
$aFormats = Doclad::getAllFormats();
$sIdFormat = Html::getInputId($model, 'doc_format');

$aButtons = [];
$aButtons = [];
foreach($aFormats As $k=>$v) {
    if( $k == 0 ) {
        continue;
    }
    $aButtons[] = Html::button(
        $v,
        [
            'class' => 'btn ' . ($model->doc_format == $k ? 'btn-success' : 'btn-default') . ' buttonformat',
            'data-formatval'=>$k,
        ]
    );
}

$sJs = <<<EOT
var aStatusButtons = jQuery(".buttonformat"),
    oState = jQuery("#{$sIdFormat}"),
    nState = oState.val();

aStatusButtons.on(
    "click",
    function(event){
        var oCur = jQuery(this);
        event.preventDefault();

        aStatusButtons.removeClass("btn-success");

        oState.val(oCur.data("formatval"));

        oCur.addClass("btn-success");

        return false;
    }
);
EOT;

$this->registerJs($sJs);

?>

<div class="col-xs-12">
    <strong>Формат представления работы</strong>
</div>

<div class="col-xs-6">
    <?= implode(' ', $aButtons) ?>
    <?= $form->field($model, 'doc_format', ['template' => '{input}{error}'])->hiddenInput() ?>
</div>

<div class="col-xs-6">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
</div>

<div class="clearfix"></div>

<?php ActiveForm::end(); ?>
