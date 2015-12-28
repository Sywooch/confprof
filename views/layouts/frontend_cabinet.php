<?php

/* @var $this \yii\web\View */
/* @var $content string */
/* @var $conference Conference */

use yii\helpers\Html;
use yii\bootstrap\Nav;

use app\assets\UserAsset;
use app\models\Conference;

UserAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="main height_100 color_razvod">
    <div class="top">
        <div class="container bg_white">
            <div class="row lio_top">
                <div class="col-xs-8 top_bg">
                    <!--metka main banner-->
                    <div class="logo_text"><a href="/" style="font-weight: 400; margin-top:30px; color:#545454; display:inline-block; margin-left:100px; font-size:30px;">Научно-практические конференции</a></div>
                </div>
                <div class="col-xs-4 lio_login">
                    <a class="lich_kab" href="/cabinet">Личный кабинет</a>
                    <?php //<a class="lich_kab" href="#">Участвовать</a> ?>
                </div>
            </div>
            <div class="row main_menu">
<?php

//    echo Nav::widget([
//        'options' => ['class' => 'nav menu'],
//        'items' => [
//            ['label' => 'О конференции', 'url' => ['/'.$activeController->id.'/index']],
//            ['label' => 'Подать заявку', 'url' => ['/'.$activeController->id.'/register']],
//            ['label' => 'Календарь', 'url' => ['/'.$activeController->id.'/calendar']],
//            ['label' => 'Пойти гостем', 'url' => ['/'.$activeController->id.'/guest']],
////            ['label' => 'Contact', 'url' => ['/site/contact']],
////            Yii::$app->user->isGuest ?
////                ['label' => 'Login', 'url' => ['/site/login']] :
////                [
////                    'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
////                    'url' => ['/site/logout'],
////                    'linkOptions' => ['data-method' => 'post']
////                ],
//        ],
//    ]);

?>
            </div>
        </div>
    </div>



    <div class="main_block">
        <div class="container bg_white">
            <div class="row">
                <div class="col-xs-12">
                    <!--metka BefComp-->
                    <div class="row">
                        <div class="col-xs-9 component">
                            <div id="system-message-container"></div>
                            <?= $content ?>
                        </div>
                        <div class="col-xs-3">
                            <!--metka Right_colmn-->
                        </div>

                    </div>
                    <div class="row">
                        <!--metka AftComp-->

                    </div>
                </div>
            </div>

        </div>
    </div>



</div>

<div class="footer">
    <div class="container lio_footer">
        <div class="row mg-t-20">
            <div class="col-xs-7">
                <p>2015 © Методическое сопровождение проекта: Городской методический центр <a href="http://mosmetod.ru/">www.mosmetod.ru</a></p>
            </div>
            <div class="col-xs-4 col-xs-offset-1">
                <p>2015 © Разработка и поддержка <a href="http://temocenter.ru/">ТемоЦентр</a></p>
            </div>
        </div>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
