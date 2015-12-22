<?php

namespace app\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\filters\AccessControl;

use app\models\Conference;
use app\models\ConferenceSearch;
use app\models\User;
use app\models\LoginForm;
use app\models\Doclad;

/**
 * ConferenceController implements the CRUD actions for Conference model.
 */
class BaseConferenceController extends Controller
{
    public $conferenceId = null;

    public $layout = 'frontend_conference';

//    public function behaviors()
//    {
//        return [
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['post'],
//                ],
//            ],
//            'access' => [
//                'class' => AccessControl::className(),
//                'only' => ['index', 'view', 'testuserdata', ],
//                'rules' => [
//                    [
//                        'allow' => true,
//                        'actions' => ['index', 'view', 'testuserdata', ],
//                        'roles' => ['@'],
//                    ],
//                ],
//            ],
//        ];
//    }

    /**
     * Show conference info
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->actionInfo();
    }

    /**
     * Show conference info
     * @return mixed
     */
    public function actionInfo()
    {
        return $this->render(
            '//userconf/info',
            [
                'conference' => $this->findConferenceModel(),
            ]
        );
    }

    /**
     * Show guest register form
     * @return mixed
     */
    public function actionGuest()
    {
        return $this->render('guest', []);
    }

    /**
     * Displays
     * @param integer $id
     * @return mixed
     */
    public function actionRegister()
    {
        if( Yii::$app->user->isGuest ) {
            $oConference = $this->findConferenceModel();
            $model = new User();
            $model->us_conference_id = $oConference->cnf_id;
            $model->scenario = 'register';

            if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->render(
                    '//user/ok_register',
                    [
                        'conference' => $oConference,
                        'model' => $model,
                    ]
                );
            } else {
                return $this->render(
                    '//user/_formregister',
                    [
                        'conference' => $oConference,
                        'model' => $model,
                    ]
                );
            }
        }
        else {
            return $this->addDoclad();
        }
    }

    /**
     * Displays
     * @param integer $id
     * @return mixed
     */
    public function actionConfirm($id)
    {
        $oConference = $this->findConferenceModel();
        return $this->render(
            '//user/confirm_register',
            [
                'conference' => $oConference,
            ]
        );
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $a = Doclad::getAllList([
                'doc_us_id' => Yii::$app->user->getId(),
            ]);
            return $this->redirect([empty($a) ? 'register' : 'list']);
        }
        return $this->render('//site/login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Добавляем доклад
     * @return string
     */
    public function addDoclad()
    {
        $oConference = $this->findConferenceModel();
        $model = new Doclad();

        $model->aSectionList = ArrayHelper::map($oConference->sections, 'sec_id', 'sec_title');
        $model->setDocType((Yii::$app->user->identity->us_group == User::USER_GROUP_ORGANIZATION) ? Doclad::DOC_TYPE_ORG : Doclad::DOC_TYPE_PERSONAL);
        $model->doc_us_id = Yii::$app->user->getId();

        $model->scenario = 'create';

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['list']);
        } else {
            return $this->render('//doclad/create', [
                'model' => $model,
                'conference' => $oConference,
            ]);
        }
    }


    /**
     * Finds the Conference model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @return Conference the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findConferenceModel()
    {
        if ( ($this->conferenceId !== null) && (($model = Conference::findOne($this->conferenceId)) !== null) ) {
            return $model;
        } else {
            throw new NotFoundHttpException('Не найдена информация о конференции');
        }
    }

}
