<?php

use yii\helpers\Html;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Doclad */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="row">
    <div class="col-xs-12">
        <div class="lio_form_WLine"></div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="lio_block_header">Файл</div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="lio_form_line"></div>
    </div>
</div>


<?php

echo $form
->field(
    $model,
    'file',
    ['template' => "{input}{error}"]
)
->widget(
    FileInput::classname(),
    [
//        'options' => ['accept'=>'image/*'],
        'pluginOptions'=>[
            'language' => 'ru',
            'allowedPreviewTypes' => [],
            'allowedFileExtensions' => Yii::$app->params['doclad.file.ext'],
            'maxFileSize' => Yii::$app->params['doclad.file.maxsize'],
            'maxFileCount' => 1,
        ]
    ]
);

?>

