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

<p>Доклад <?= Html::encode($data->doc_subject) ?>.</p>

<?php
if( $data->doc_state == Doclad::DOC_STATUS_APPROVE ) {
?>
<?php
    if( $data->section->sec_cnf_id == 1 ) {
        // Для инженеров будущего
        ?>
            <p>Уважаемый участник Конференции &laquo;Инженеры будущего&raquo;!</p>
            <p>Ваш доклад согласован. Для дальнейшего участия Вам необходимо:</p>
            <ol>
                <li>Создать папку, назвать её, указав свою фамилию, имя, отчество.</li>
                <li>Папка должна содержать следующие материалы:
                    <ul>
                        <li>выполненную работу;</li>
                        <li>тезисы выступления;</li>
                        <li>презентационные материалы (мультимедийную презентацию, видеофильм, фотографии и т.д.)</li>
                        <li>пояснительную записку, в которой необходимо указать желаемую форму Вашего выступления – стендовый доклад или устное выступление;</li>
                        <li>для стендового доклада укажите необходимое оборудование, минимальную площадь для демонстрации Вашего продукта, требуется ли подключение к электросети.</li>
                    </ul>
                </li>
                <li>Папку необходимо заархивировать в формате ZIP.</li>
                <li>Заархивированную папку нужно разместить на пространстве файлового хостинга общего доступа.</li>
                <li>Ссылку с возможностью &laquo;просмотра&raquo; размещённого материала Вы отправляете по адресу eng@edu.mos.ru</li>
            <ol>
            <p>Требования к материалам содержатся на сайте проекта в рубрике <a href="http://profil.mos.ru/inj/anonsy-inzhenernyj-klass/112-otkrytaya-nauchno-prakticheskaya-konferentsiya-inzhenery-budushchego.html">АНОНСЫ</a> .</p>
        <?php
    }
    else {
        // Для остальных
        ?>
            <p>Ваш доклад согласован и Вы можете добавить к нему файл с работой.</p>
        <?php
    }
?>

<?php
} else {
?>
<p>Сделаны следующие замечания: <?= nl2br(Html::encode($data->doc_comment)) ?>.</p>
<?php
}
?>

<?php
if( $data->section->sec_cnf_id != 1 ) {
    // Для остальных
?>
    <p>Ссылка для перехода к изменению доклада: <?= Html::a($sLink, $sLink) ?>.</p>
<?php
}
?>



