<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

use kartik\file\FileInput;
use app\models\File;

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

$pluginOptions = [
    'language' => 'ru',
    'allowedPreviewTypes' => [],
    'showUpload' => false,
    'allowedFileExtensions' => Yii::$app->params['doclad.file.ext'],
    'maxFileSize' => Yii::$app->params['doclad.file.maxsize'],
    'maxFileCount' => 1,
    'layoutTemplates' => [
        'actions' => '{delete}',
    ],
];

if( count($model->files) > 0 ) {
    // не заморачиваемся с существующими файлами - делаем отдельные ссылки на удаление
    $aPrev = [];
    $aConf = [];
    foreach($model->files As $ob) {
        /** @var File $ob */
//        $aPrev[] = "<div class='file-preview-text'>"
//            . "<h2><i class='glyphicon glyphicon-file'></i></h2>"
//            . $ob->file_orig_name
//            . "</div>";
        $aPrev[] = // '<div class="file-preview-frame" title="'.$ob->file_orig_name.'">' //  style="width:160px;height:160px;"
            '<div class="file-preview-other-frame">'
            . '<div class="file-preview-other">'
            . '<span class="file-icon-4x"><i class="glyphicon glyphicon-file"></i></span>'
            . '</div>'
            . '</div>'
            . '<div class="file-preview-other-footer">'
            . '<div class="file-thumbnail-footer">'
            . '<div class="file-footer-caption" title="'.$ob->file_orig_name.'">'.$ob->file_orig_name.'</div>'
            . '</div>'
            . '</div>';
//            . '</div>';

        $aConf[] = [
            'caption' => $ob->file_orig_name,
            'url' => Url::to([Yii::$app->controller->getUniqueId() . '/deletefile', 'fileid' => $ob->file_id]),
            'key' => $ob->file_id,
            'extra' => [
                'doc' => $model->doc_id,
            ],
        ];

        echo '<div class="" style="margin: 0 0 12px;">' . Html::a(
                $ob->file_orig_name,
                str_replace(DIRECTORY_SEPARATOR, '/', $ob->file_name)
            )
            . ' '
            . Html::a(
                '<i class="glyphicon glyphicon-remove"></i>',
                [Yii::$app->controller->getUniqueId() . '/deletefile', 'id' => $model->doc_id, 'fileid' => $ob->file_id],
                [
                    'class' => 'post-request-link-delete-file',
                ]
            )
            . '</div>';

    }

    echo '<div class="form-file-region" style="display: none;">';
    $sJs = <<<EOT
jQuery(".post-request-link-delete-file").on(
    "click",
    function(event) {
        event.preventDefault();
        var oLink = jQuery(this),
            oParent = oLink.parent();
        jQuery.ajax({
            dataType: "json",
            url: oLink.attr("href"),
            data: {},
            success: function(data, textStatus, jqXHR) {
                if( !("error" in data) ) {
                    oParent.fadeOut();
                    jQuery(".form-file-region").fadeIn();
                }
            }
        });
        return false;
    }
);
EOT;
    $this->registerJs($sJs, View::POS_READY, 'upload-file-action');

//    $pluginOptions['initialPreview'] = $aPrev;
//    $pluginOptions['initialPreviewConfig '] = $aConf;
}

if( count($model->files) == 0 ) {
    echo '<div class="alert-success" style="padding: 15px 15px 0; margin-bottom: 20px; border: 2px solid #3c763d;"><p>Загрузите файл работы</p></div>';
}

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
        'pluginOptions' => $pluginOptions,
    ]
);

if( count($model->files) > 0 ) {
    echo '</div>';
}
