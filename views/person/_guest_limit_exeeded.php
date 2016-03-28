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

                    <div class="row">
                        <div class="col-xs-12">
                            Регистрация гостем невоможна: достигнуто максимальное количество гостей. <!-- <?php echo $oConference->cnf_guestlimit . ' .. ' . $oConference->getGuestcount() ?> -->
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
