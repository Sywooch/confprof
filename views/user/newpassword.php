<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-newpassword">
    <!-- h1><?= Html::encode($this->title) ?></h1 -->

    <!-- p>Please fill out the following fields to login:</p -->

    <?php

    if( !Yii::$app->session->hasFlash('success') ) {
        $form = ActiveForm::begin([
            'id' => 'newpassword-form',
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
                'labelOptions' => ['class' => 'col-lg-2 control-label'],
            ],
        ]);
        ?>

        <?= $form->field($model, 'password') ?>
        <?= $form->field($model, 'password_repeat') ?>

        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <?= Html::submitButton('Установить пароль', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

        <?php
        ActiveForm::end();
    }

    ?>
</div>
