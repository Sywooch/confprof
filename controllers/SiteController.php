<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

use app\models\LoginForm;
use app\models\ContactForm;
use app\models\RestorepasswordForm;
use app\components\Notificator;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        if( Yii::$app->id == 'user' ) {
            $this->layout = 'frontend01';
        }
        else {
            if( Yii::$app->user->isGuest ) {
                return $this->redirect(['site/login']);
            }
//            return $this->render('admin');
        }
        return $this->render('index');
    }

    public function actionRestorepassword()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new RestorepasswordForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            Yii::$app->session->setFlash('success', 'На Ваш email отправлено письмо с сылкой для установки нового пароля.');

            $oUser = $model->getUser();
            $oUser->us_key = Yii::$app->security->generateRandomString() . time();
            $oUser->save(false, ['us_key']);

            $oNotify = new Notificator([$oUser], $oUser, 'restorepassword_mail');
            $oNotify->notifyMail('Запрос на изменение пароля на сайте "' . Yii::$app->name . '"');

            return $this->refresh();
//            return $this->redirect('/');
        }

        return $this->render('restorepassword', [
            'model' => $model,
        ]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if( Yii::$app->user->can(User::USER_GROUP_MODERATOR) ) {
                return $this->redirect('/admin');
            }
            return $this->redirect('/cabinet');
//            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect('/');
//        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
}
