<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Doctalk */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="doctalk-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'dtlk_us_id')->textInput() ?>

    <?= $form->field($model, 'dtlk_doc_id')->textInput() ?>

    <?= $form->field($model, 'dtlk_text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'dtlk_created')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
