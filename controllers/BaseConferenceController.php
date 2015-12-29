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
use app\models\Member;
use app\models\Docmedal;

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
            'validateConsultant' => [
                'class' => MultirowsBehavior::className(),
                'model' => Person::className(),
            ],
            'validateMedals' => [
                'class' => MultirowsBehavior::className(),
                'model' => Docmedal::className(),
            ],
            'validateMembers' => [
                'class' => MultirowsBehavior::className(),
                'model' => Member::className(),
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
     * Show doclad info
     * @param $id integer
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('//doclad/fullview', [
            'conference' => $this->findConferenceModel(),
            'model' => $this->findModel($id),
        ]);

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
        $model = new Person();
        $model->prs_type = Person::PERSON_TYPE_GUEST;
        $oConference = $this->findConferenceModel();

        $model->aSectionList = ArrayHelper::map($oConference->sections, 'sec_id', 'sec_title');

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->render(
                '//person/ok_guest',
                [
                    'oConference' => $oConference,
                    'model' => $model,
                ]
            );
        }

        return $this->render(
            '//person/_form_guest',
            [
                'oConference' => $oConference,
                'model' => $model,
            ]
        );
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

            if( $model->doc_type == Doclad::DOC_TYPE_PERSONAL ) {
                // для доклада от персонального участника нужен хотя бы 1 руководитель
                $resultConsultant = $this->getBehavior('validateConsultant')->validateData();
                $dataConsultant = $this->getBehavior('validateConsultant')->getData();
                if( count($dataConsultant['data']) == 0 ) {
                    $sId = Html::getInputId($model, 'doc_subject');

                    if( !isset($aValidate[$sId]) ) {
                        $aValidate[$sId] = [];
                    }
                    $aValidate[$sId][] = 'Необходимо указать руководителя.';
                }
            }
            else {
                $resultConsultant = [];
            }

            $resultMembers = $this->getBehavior('validateMembers')->validateData();

            $resultMedals = $this->getBehavior('validateMedals')->validateData();

            $aRes = array_merge($aValidate, $resultConsultant, $resultMedals, $resultMembers);

            Yii::info('addDoclad(): return json ' . print_r($aRes, true));
            return $aRes;

//            Yii::$app->response->format = Response::FORMAT_JSON;
//            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) ) {
            $bOk = $model->save();
            if( $bOk ) {
                $bNew = $model->isNewRecord;

                if( $model->doc_type == Doclad::DOC_TYPE_PERSONAL ) {
                    $dataConsultant = $this->getBehavior('validateConsultant')->getData();
//                    Yii::info('dataConsultant = ' . print_r($dataConsultant, true));
                    $bOk = $bOk && $model->saveConsultants($dataConsultant['data']);
                }

                $dataMember = $this->getBehavior('validateMembers')->getData();
                $bOk = $bOk && $model->saveMembers($dataMember['data']);

                $dataMedals = $this->getBehavior('validateMedals')->getData();
                $model->saveMedals($dataMedals['data']);

                return $this->redirect(['list']);
            }
            else {
                Yii::info('Error save: ' . print_r($model->getErrors(), true));
            }

            if( !$bOk ) {
                Yii::info('Error doclad !!!!!!!');
            }
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

    public function actionRegthankyou() {
        return $this->render(
            '//person/ok_guestregistr', [
            ]
        );
    }

    public function actionDelete() {
        return $this->redirect(['list']);
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
