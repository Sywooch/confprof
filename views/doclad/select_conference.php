<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;

use app\models\Conference;

/* @var $this yii\web\View */
/* @var $model app\models\Doclad */

$defaultOptions = [
    'class' => 'btn btn-default'
];

$partial = !isset($partial) ? true : $partial;
$options = !isset($options) ? $defaultOptions : $options;

if( !$partial ) {
    $this->title = 'Выбор конференции';
    $this->params['breadcrumbs'][] = ['label' => 'Доклады', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;

    echo '<h1>'.Html::encode($this->title).'</h1>';
}

$aConf = ArrayHelper::map(Conference::getAll(), 'cnf_id', 'cnf_shorttitle');

?>

<div class="btn-toolbar"><?php

foreach($aConf As $id=>$title) {
    echo Html::a(
        $title,
        isset($link) ? array_merge($link, ['id' => $id]) : '',
        $options
    );
}

?>

</div>