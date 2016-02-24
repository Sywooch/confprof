<?php

use yii\helpers\Html;
use app\models\Section;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="row">
    <div class="col-xs-9">
        <?= $form->field($model, 'sectionids')->listBox(Section::getSectionList(), ['multiple' => 'multiple']) //(User::getAllGroups()) // textInput(['maxlength' => true]) ?>
    </div>
</div>

