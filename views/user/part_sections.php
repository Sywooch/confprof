<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\Section;
use app\models\Usersection;
use app\models\UsersectionForm;
use mosedu\multirows\MultirowsWidget;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

$aSectionModels = []; // $model->sections;
$aSectionModels = ArrayHelper::map(
    $model->sections,
    'usec_id',
    function($el){
        $ob = new UsersectionForm();
        $ob->attributes = [
            'sectid' => $el->usec_section_id,
            'isprime' => $el->usec_section_primary,
        ];
        return $ob;
    }
);
//foreach( $model->sections As $v) {
//    /** @var Usersection $v */
//    $ob = new UsersectionForm();
//    $ob->attributes = ['sectid' => $v->usec_section_id, 'isprime' => $v->usec_section_primary, ];
//    $aSectionModels[] = $ob;
//}

// $data = Section::getSectionList();

//$optSelect2Goods = [
//    'language' => 'ru',
//    'data' => $data,
//    'pluginOptions' => [
////        'debug' => true,
//        'allowClear' => true,
//        'minimumInputLength' => 0,
//    ],
//
//    'options' => [
//        'multiple' => true,
//        'placeholder' => 'Выберите подарки ...',
//    ],
//];

?>
<div class="row">
    <div class="col-sm-11">&nbsp;</div>
    <div class="col-sm-1">

    <?php
echo Html::a(
    Html::tag('span', '', ['class' => 'glyphicon glyphicon-plus']),
    '#',
    [
        'class' => 'btn btn-success add-section',
    ]
);
?>
    </div>
</div>
<?php

echo MultirowsWidget::widget(
    [
        'model' => UsersectionForm::className(),
        'form' => $form,
        'records' => $aSectionModels,
        'rowview' => '@app/views/usersection/_editwithuser.php', // d:\projects\web\confprof\views\usersection\_editwithuser.php
        'tagOptions' => ['class' => 'usersection-row'],
//        'defaultattributes' => ['userid' => $model->us_id],
        'addlinkselector' => '.add-section',
        'dellinkselector' => '.remove-section',
        'afterInsert' => 'function(ob){}',
        'afterDelete' => 'function(){}',
        'canDeleteLastRow' => false,
//        'script' => $sScript,
    ]
);

?>

<div class="row">
    <div class="col-xs-6">
        <?= '' // $form->field($model, 'sectionids')->listBox(Section::getSectionList(), ['multiple' => 'multiple']) //(User::getAllGroups()) // textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-xs-6">
        <?= '' // $form->field($model, 'sectionids')->listBox(Section::getSectionList(), ['multiple' => 'multiple']) //(User::getAllGroups()) // textInput(['maxlength' => true]) ?>
    </div>
</div>

