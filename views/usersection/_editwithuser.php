<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\ArrayHelper;
use app\models\Section;

use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Userdata */
/* @var $form yii\widgets\ActiveForm */
/* @var $index int */
/* @var $startindex int */

$sId = $index;

$aSections = Section::getSectionList();

?>

<div class="row">
    <div class="col-sm-7">
        <?= $form
            ->field($model, '[' . $sId . ']sectid', ['template' => "{input}\n{hint}\n{error}"])
            ->dropDownList($aSections)
        //            ->widget(Select2::classname(), $aResource) ?>
    </div>

    <div class="col-sm-4">
        <?= $form
            ->field($model, '[' . $sId . ']isprime', ['template' => "{input}\n{hint}\n{error}"])
            ->checkbox()
        ?>
    </div>

    <div class="col-sm-1">
        <?= Html::a(
            Html::tag('span', '', ['class' => 'glyphicon glyphicon-remove']),
            '#',
            [
                'class' => 'btn btn-danger remove-section',
            ]
        ) ?>
    </div>

    <div class="clearfix"></div>
</div>
