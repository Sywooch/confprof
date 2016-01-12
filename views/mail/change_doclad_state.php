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
use app\models\Doclad;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $data app\models\Doclad */

$aLink = ['cabinet/update', 'id' => $data->doc_id];
$sLink = str_replace('/admin/', '/', Url::to($aLink, true));
?>

<p>Здравствуйте.</p>

<p>Модератор изменил статус Вашего доклада на сайте <?= Yii::$app->name ?>.</p>

<?php
if( $data->doc_state == Doclad::DOC_STATUS_APPROVE ) {
?>
<p>Ваш доклад согласован и Вы можете добавить к нему файл с работой.</p>
<?php
} else {
?>
<p>Сделаны следующие замечания: <?= nl2br(Html::encode($data->doc_comment)) ?>.</p>
<?php
}
?>
<p>Ссылка для перехода к изменению доклада: <?= Html::a($sLink, $sLink) ?>.</p>

