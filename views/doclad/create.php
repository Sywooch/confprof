<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Doclad */
/* @var $conference app\models\Conference */

$sTitle = $model->isNewRecord ? 'Новый доклад' : 'Редактирование доклада';
$this->title = $sTitle . ', ' . $conference->cnf_title;
$this->params['breadcrumbs'][] = ['label' => 'Доклады', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12">

        <div class="row"> <!--Начало - Заголовок формы-->
            <div class="col-xs-12">
                <h3 class="lio_form_header"><?= Html::encode($sTitle) ?></h3>
            </div>
        </div><!-- Конец - Заголовок формы-->

        <div class="row"><!--Начало - Блока формы-->
            <div class="col-xs-12 lio_form_block doclad-create">

                <?= $this->render('_form', [
                    'model' => $model,
                    'conference' => $conference,
                ]) ?>

            </div>
        </div><!-- Конец - Блока формы-->

    </div>
</div>
