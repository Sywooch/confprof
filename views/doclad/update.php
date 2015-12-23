<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Doclad */

$this->title = 'Изменение доклада'; //  . ' ' . $model->doc_id;
$this->params['breadcrumbs'][] = ['label' => 'Доклады', 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->doc_id, 'url' => ['view', 'id' => $model->doc_id]];
$this->params['breadcrumbs'][] = $model->doc_subject;
// <div class="doclad-update">
// </div>
?>

<div class="row">
    <div class="col-xs-12">

        <div class="row"> <!--Начало - Заголовок формы-->
            <div class="col-xs-12">
                <h3 class="lio_form_header"><?= Html::encode($this->title) ?></h3>
            </div>
        </div><!-- Конец - Заголовок формы-->

        <div class="row"><!--Начало - Блока формы-->
            <div class="col-xs-12 lio_form_block doclad-create">

                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>

            </div>
        </div><!-- Конец - Блока формы-->

    </div>
</div>
