<?php

/**
 * Список сообщение от модераторов
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\grid\GridView;
use yii\widgets\Pjax;

use app\models\Doclad;

/* @var $this yii\web\View */
/* @var $doclad app\models\Doclad */
/* @var $model app\models\Doctalk */
/* @var $form yii\widgets\ActiveForm */
/* @var $searchModel app\models\DoctalkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


// <div class="doclad-form">
// </div>
?>

<div class="col-xs-12" style="border-top: 1px solid #aaaaaa; margin-top: 15px; margin-bottom: 15px; padding-top: 15px;">
    <strong>Комментарии к докладу</strong>
</div>
    <div class="doclad-index">

        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <!-- p>
        <?= '' // Html::a('Create Doclad', ['create'], ['class' => 'btn btn-success']) ?>
    </p -->

        <?php Pjax::begin(['timeout' => 12000]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{pager}",
            'emptyText' => 'Нет комментариев к докладу. Вы можете оставить комментарий в форме ниже.',
            'filterModel' => null,
            'headerRowOptions' => ['style' => 'display: none;'],
            'columns' => [
                [
                    'class' => 'yii\grid\DataColumn',
                    'attribute' => 'dtlk_us_id',
                    'contentOptions' => ['style' => 'width: 320px;'],
                    'format' => 'raw',
                    'value' => function ($model, $key, $index, $column) {
                        /** @var $model app\models\Doctalk */
                        return (
                            $model->autor ?
                                (
                                    (
                                        !empty($model->autor->us_name) ?
                                            ( Html::encode($model->autor->us_name)
//                                                . '<br />'
                                            ) :
                                            Html::encode($model->autor->us_email) // ''
                                    )
//                                    . Html::encode($model->autor->us_email)
                                    . '<br />'
                                ) :
                                ''
                        )
                        . date('d.m.Y H:i', strtotime($model->dtlk_created));
                    },
                ],
                'dtlk_text',
//                [
//                    'class' => 'yii\grid\DataColumn',
//                    'attribute' => 'dtlk_text',
//                ],
            ],
        ]); ?>
        <?php Pjax::end(); ?>

    </div>


<?php $form = ActiveForm::begin([
        'id' => 'doctalk-form',
        'action' => ['doctalk/create', ], // 'docid'=>$model->doc_id,
        'fieldConfig' => [
            'template' => '<div class="col-xs-2">{label}</div><div class="col-xs-10">{input}{error}</div>',
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

?>

<div class="col-xs-10">
    <?= $form->field($model, 'dtlk_doc_id', ['template' => '{input}{error}'])->hiddenInput() ?>
    <?= $form->field($model, 'dtlk_text')->textarea(['rows' => 5,]) ?>
</div>

<div class="col-xs-2">
    <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
</div>

<div class="clearfix"></div>

<?php

ActiveForm::end();

$sJs = <<<EOT
var oForm = jQuery('#{$form->options['id']}');

oForm
.on('afterValidate', function (event, messages) {
//    console.log("afterValidate()", event);
//    console.log(messages);
})
.on('submit', function (event) {
//    console.log("submit()");
    var formdata = oForm.data().yiiActiveForm;
    event.preventDefault();
    if( formdata.validated ) {
        // имитация отправки
        formdata.validated = false;
        formdata.submitting = true;

        location.reload();
    }
    return false;
});

EOT;

$this->registerJs($sJs, View::POS_READY, 'submit_talk_form');
