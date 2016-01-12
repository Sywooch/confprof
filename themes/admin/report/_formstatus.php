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
$aStatus = Doclad::getAllStatuses();

$sIdState = Html::getInputId($model, 'doc_state');
$aButtons = [];
foreach($aStatus As $k=>$v) {
    if( $k == 0 ) {
        continue;
    }
    $aButtons[] = Html::button(
        $v,
        [
            'class' => 'btn ' . ($model->doc_state == $k ? 'btn-success' : 'btn-default') . ' buttonstatus',
            'data-statusval'=>$k,
            'data-showcomment' =>($k != Doclad::DOC_STATUS_APPROVE) ? 1 : 0,
        ]
    );
}

$sJs = <<<EOT
var aStatusButtons = jQuery(".buttonstatus"),
    oComment = jQuery(".commentarea"),
    oState = jQuery("#{$sIdState}"),
    nState = oState.val();

oComment.hide();

aStatusButtons.each(function(index){
    var oCur = jQuery(this);
    if( nState == oCur.data("statusval") ) {
        if( oCur.data("showcomment") == 1 ) {
            oComment.show();
        }
        else {
            oComment.hide();
        }
    }
});

aStatusButtons.on(
    "click",
    function(event){
        var oCur = jQuery(this);
        event.preventDefault();

        aStatusButtons.removeClass("btn-success");

        oState.val(oCur.data("statusval"));

        oCur.addClass("btn-success");

        if( oCur.data("showcomment") == 1 ) {
            oComment.show();
        }
        else {
            oComment.hide();
        }

        return false;
    }
);
EOT;

$this->registerJs($sJs);

?>

<div class="col-xs-12">
    <strong>Согласование</strong>
</div>

<div class="col-xs-6">
    <?= implode(' ', $aButtons) ?>
    <?= $form->field($model, 'doc_state', ['template' => '{input}{error}'])->hiddenInput() ?>
</div>

<div class="col-xs-6">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
</div>

<div class="clearfix"></div>

<div class="col-xs-6 commentarea">
    <?= $form->field($model, 'doc_comment')->textarea(['rows'=>3]) ?>
</div>

<div class="clearfix"></div>

<?php ActiveForm::end(); ?>
