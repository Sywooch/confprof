<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\UserAsset;
use app\components\AppendAppIcons;
use app\assets\EduCounterAsset;
use app\components\PageCounters;

UserAsset::register($this);
EduCounterAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?= AppendAppIcons::getIconList() ?>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?php
$aMsg = ['danger', 'success', 'info', 'warning', ];
foreach($aMsg As $v) {
    if( Yii::$app->session->hasFlash($v) ) {
        $s = Yii::$app->session->getFlash($v);
        echo '<div class="alert alert-'.$v.'" role="alert" style="margin-top: 15px;">'
            . (strpos($s, '<a') !== false ? $s : Html::encode($s))
            . '</div>'
            . '<div class="clearfix"></div>';
    }
}
?>

<?= $content ?>

<?= PageCounters::insertCounters(); ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
