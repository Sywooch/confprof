<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;

use app\models\Doclad;
use app\models\File;

/* @var $this yii\web\View */
/* @var $model app\models\Doclad */

$this->title = $model->doc_subject;
$this->params['breadcrumbs'][] = ['label' => 'Доклады', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// ***************************************************************************************************
$attributes = [
//    'doc_id',
    [
        'attribute' => 'doc_type',
        'value' => $model->typeTitle(),
    ],
    [
        'attribute' => 'doc_sec_id',
        'value' => $model->section ? (Html::encode($model->section->sec_title) . '<br />' . Html::encode($model->section->conference->cnf_title)) : '',
        'format' => 'raw',
    ],
    'doc_subject',
    'doc_description:ntext',
    [
        'attribute' => 'doc_created',
        'value' => date('d.m.Y H:i:s', strtotime($model->doc_created)),
    ],
    [
        'attribute' => 'doc_lider_fam',
        'value' => Html::tag('strong', Html::encode($model->getLeadername(false))),
        'format' => 'raw',
    ],
//    'doc_lider_fam',
//    'doc_lider_name',
//    'doc_lider_otch',
    'doc_lider_email:email',
    'doc_lider_phone',
//    'ekis_id',
    'doc_lider_org',
];

// ***************************************************************************************************
// Добавим участников
if( count($model->members) > 0 ) {
    $sValue = implode(
        '<br />',
        ArrayHelper::map(
            $model->members,
            'prs_id',
            function($ob, $default) {
                /** @var $ob app\models\Person */
                return Html::tag('strong', Html::encode($ob->getPersonname(false))) . ' ' . Html::encode($ob->prs_org);
            }
        )
    );

    $attributes = array_merge(
        $attributes,
        [
            [
                'attribute' => 'members',
                'format' => 'raw',
                'value' => $sValue,
            ],
        ]
    );
}

// ***************************************************************************************************
// Добавим руководителей
if( count($model->persons) > 0 ) {
    $sValue = implode(
        '<br />',
        ArrayHelper::map(
            $model->persons,
            'prs_id',
            function($ob, $default) {
                /** @var $ob app\models\Person */
                return Html::tag('strong', Html::encode($ob->getPersonname(false)))  . ' ' . Yii::$app->formatter->format($ob->prs_email, 'email') . ' ' . Html::encode($ob->prs_org);
            }
        )
    );

    $attributes = array_merge(
        $attributes,
        [
            [
                'attribute' => 'consultants',
                'format' => 'raw',
                'value' => $sValue,
            ],
        ]
    );
}

// ***************************************************************************************************
if( $model->doc_type == Doclad::DOC_TYPE_PERSONAL ) {
    $attributes = array_merge(
        $attributes,
        [
            'doc_lider_group',
            'doc_lider_level',
        ]
    );
}
else {
    $attributes = array_merge(
        $attributes,
        [
            'doc_lider_position',
            'doc_lider_lesson',
        ]
    );
}

// ***************************************************************************************************
// Добавим файлы
$aFiles = $model->files;
if( count($aFiles) > 0 ) {
    $sFiles = array_reduce(
        $aFiles,
        function($sRes, $item){
            /** @var File $item */
            return Html::a($item->file_orig_name, str_replace(DIRECTORY_SEPARATOR, '/', $item->file_name))
                . ($sRes != '' ? '<br />' : '')
                . $sRes;
        },
        ''
    );

    $attributes = array_merge(
        $attributes,
        [
            [
                'attribute' => 'file',
                'format' => 'raw',
                'value' => $sFiles,
            ],
        ]
    );
}

// ***************************************************************************************************
?>
<div class="doclad-view">

    <h1><?= Html::encode($this->title) ?></h1>
<?php /*
    <p>
<?php Html::a('Update', ['update', 'id' => $model->doc_id], ['class' => 'btn btn-primary']) ?>
<?php Html::a('Delete', ['delete', 'id' => $model->doc_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p> */
?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $attributes,
    ]) ?>

</div>
