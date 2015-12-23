<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Docmedal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-xs-4">
        <?= $form->field($model, '[' . $index . ']mdl_competition')->textInput(['maxlength' => true, 'class'=>'lio_input', 'placeholder'=>$model->getAttributeLabel('mdl_competition'),]) ?>
    </div>
    <div class="col-xs-4">
        <?= $form->field($model, '[' . $index . ']mdl_title')->textInput(['maxlength' => true, 'class'=>'lio_input', 'placeholder'=>$model->getAttributeLabel('mdl_title'),]) ?>
    </div>
    <div class="col-xs-4">
        <div class="add_remove">
            <a class="lio_add_el del_medal">Удалить</a>
        </div>
    </div>
</div>

