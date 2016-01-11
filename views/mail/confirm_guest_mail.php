<?php
/**
 * Created by PhpStorm.
 * User: Vik
 * Date: 09.11.2015
 * Time: 0:17
 */

//use yii;
use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\models\Person */
/* @var $section app\models\Section */
/* @var $conference app\models\Conference */

$aLink = ['person/confirmemail', 'key' => $model->prs_confirmkey];
$section = $model->section;
$conference = $section->conference;

?>

<p>Здравствуйте <?php echo Html::encode($model->personname); ?>.</p>

<p>Вы зарегистрировались в качестве гостя на сайте <?= Yii::$app->name ?>,
    конференция <?php echo Html::encode($conference->cnf_title); ?>,
    секция <?php echo Html::encode($section->sec_title); ?>.
</p>

<p>Для подтверждения email перейдите по ссылке: <?= Html::a(Url::to($aLink, true), Url::to($aLink, true)) ?></p>

