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

use mosedu\multirows\MultirowsBehavior;

use app\models\Conference;
use app\models\ConferenceSearch;
use app\models\User;
use app\models\LoginForm;
use app\models\Doclad;
use app\models\DocladSearch;
use app\models\Person;

/**
 * ConferenceController implements the CRUD actions for Conference model.
 */
class BaseConferenceController extends Controller
{
    public $conferenceId = null;

    public $layout = 'frontend_conference';

    public function behaviors()
    {
        return [
            'validatePermissions' => [
                'class' => MultirowsBehavior::className(),
                'model' => Person::className(),
            ],
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
        ];
    }

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
     * @param $id integer
     * @return Response
     */
    public function actionUpdate($id)
    {
        return $this->addDoclad($id);
    }

    /**
     * Добавляем доклад
     * @param $id integer id доклада
     * @return string
     */
    public function addDoclad($id = 0)
    {
        $oConference = $this->findConferenceModel();

        if( $id == 0 ) {
            $model = new Doclad();
            $model->setDocType((Yii::$app->user->identity->us_group == User::USER_GROUP_ORGANIZATION) ? Doclad::DOC_TYPE_ORG : Doclad::DOC_TYPE_PERSONAL);
            $model->doc_us_id = Yii::$app->user->getId();
        }
        else {
            $model = $this->findModel($id);
        }

        $model->aSectionList = ArrayHelper::map($oConference->sections, 'sec_id', 'sec_title');

        $model->scenario = 'create';

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $aValidate = ActiveForm::validate($model);
            $result = $this->getBehavior('validatePermissions')->validateData();
            $data = $this->getBehavior('validatePermissions')->getData();
            if( count($data['data']) == 0 ) {
                $sId = Html::getInputId($model, 'doc_subject');

                if( !isset($aValidate[$sId]) ) {
                    $aValidate[$sId] = [];
                }
                $aValidate[$sId][] = 'Необходимо указать руководителя.';
            }
            Yii::info('addDoclad(): return json ' . print_r($aValidate, true));
            $aRes = array_merge($aValidate, $result);
            return $aRes;

//            Yii::$app->response->format = Response::FORMAT_JSON;
//            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $bNew = $model->isNewRecord;

            $data = $this->getBehavior('validatePermissions')->getData();
            Yii::info('data = ' . print_r($data, true));

            $model->saveConsultants($data['data']);
            return $this->redirect(['list']);
        }
        else {
            Yii::info('Error save: ' . print_r($model->getErrors(), true));
        }
            return $this->render('//doclad/create', [
                'model' => $model,
                'conference' => $oConference,
            ]);
    }

    public function actionList() {
        $aDop = ['doc_us_id' => Yii::$app->user->getId()];
        $searchModel = new DocladSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $aDop);

        return $this->render(
            '//doclad/userdoclist', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]
        );
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

    /**
     * Finds the Doclad model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Doclad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Doclad::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
