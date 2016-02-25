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
use yii\db\ActiveRecord;
use yii\helpers\Html;

use app\components\Notificator;
use app\models\User;
use app\models\Doclad;
use app\models\DocladSearch;
use app\models\Person;
use app\components\ExcelexportBehavior;
use app\components\Statistics;


/**
 * ConferenceController implements the CRUD actions for Conference model.
 */
class ReportController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'changestatus', 'changeformat', 'export', 'statistics', ],
                        'roles' => [User::USER_GROUP_MODERATOR, User::USER_GROUP_ADMIN, ],
                    ],
                ],
            ],

            /*
             * Экспорт в Excel
             */
            'excelExport' => [
                'class' => ExcelexportBehavior::className(),
                'dataTitle' => 'Доклады',
                'nStartRow' => 1,
                'columnTitles' => [
                    'Тема',
                    'Тип',
                    'Конференция',
                    'Секция',
                    'Лидер',
                    'Email',
                    'Телефон',
                    'Организация',
                    'Класс / должность',
                    'Согласование',
                    'Формат',
                ],
                'columnWidth' => [
                    30,
                    20,
                    30,
                    30,
                    30,
                    20,
                    20,
                    40,
                    20,
                    20,
                    20,
                ],
                'columnValues' => [
                    'doc_subject',
                    function($model, $index) {
                        /** @var Doclad $model */
                        Yii::info('Raw: ' . $index . ' + ' . $model->doc_subject);
                        return $model->typeTitle();
                    },
                    function($model, $index) {
                        /** @var Doclad $model */
                        return $model->section->conference->cnf_title;
                    },
                    function($model, $index) {
                        /** @var Doclad $model */
                        return $model->section->sec_title;
                    },
                    function($model, $index) {
                        /** @var Doclad $model */
                        return $model->getLeadername(false);
                    },
                    'doc_lider_email',
                    'doc_lider_phone',
                    'doc_lider_org',
                    function($model, $index) {
                        /** @var Doclad $model */
                        return $model->doc_type == Doclad::DOC_TYPE_PERSONAL ?
                            ($model->doc_lider_group . ' / ' . $model->doc_lider_level) :
                            ($model->doc_lider_position . ' / ' . $model->doc_lider_lesson) ;
                    },
                    'status',
                    'format',
                ],
            ]

//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['post'],
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
//        $aDop = ['doc_us_id' => Yii::$app->user->getId()];
        $searchModel = new DocladSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render(
            'docladindex',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]
        );
    }

    /**
     * Export data
     * @return mixed
     */
    public function actionExport()
    {
        $searchModel = new DocladSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $sDir = Yii::getAlias('@webroot/assets');
        $sFileName = $sDir . DIRECTORY_SEPARATOR . 'doclad-'.date('Y-m-d-H-i-s').'.xls';
        $this->clearDestinationDir($sDir, 'xls', time() - 300);
        $this->exportToFile($dataProvider, $sFileName);

        Yii::$app->response->sendFile($sFileName);

//        return $this->renderContent(
//            Html::a(
//                'Загрузить',
//                substr($sFileName, str_replace(DIRECTORY_SEPARATOR, '/', strlen($_SERVER['DOCUMENT_ROOT'])))
//            )
//        );
    }

    /**
     * Show doclad info
     * @param $id integer
     */
    public function actionView($id)
    {
        $model = $this->findDoclad($id);
        if( $model->doc_state != Doclad::DOC_STATUS_APPROVE ) {
            $model->scenario = 'changestatus';
        }
        else {
            if( (count($model->files) > 0) && ($model->doc_format == Doclad::DOC_FORMAT_NOFORMAT) ) {
                $model->scenario = 'changeformat';
            }
        }

        return $this->changeDoclad($model);
    }

    /**
     * Change doclad status
     * @param $id integer
     */
    public function actionChangestatus($id)
    {
        $model = $this->findDoclad($id);
        $model->scenario = 'changestatus';

        return $this->changeDoclad($model);
    }

    /**
     * Change doclad status
     * @param $id integer
     */
    public function actionChangeformat($id)
    {
        $model = $this->findDoclad($id);
        $model->scenario = 'changeformat';

        return $this->changeDoclad($model);
    }

    /**
     * @param Doclad $model
     * @return array|string|Response
     */
    public function changeDoclad($model) {
        $model->_oldAttributesValues = $model->attributes;
        $model->on(ActiveRecord::EVENT_AFTER_UPDATE, function ($event) {
            /** @var Doclad $model */
            $model = $event->sender;
            if( $model->_oldAttributesValues['doc_state'] != $model->doc_state ) {
                $oNotify = new Notificator([User::findOne($model->doc_us_id)], $model, 'change_doclad_state');
                $oNotify->notifyMail('Модератор изменил статус Вашего доклада на сайте "' . Yii::$app->name . '"');
            }

            if( $model->_oldAttributesValues['doc_format'] != $model->doc_format ) {
                $oNotify = new Notificator([User::findOne($model->doc_us_id)], $model, 'change_doclad_format');
                $oNotify->notifyMail('Модератор изменил формат представления Вашего доклада на сайте "' . Yii::$app->name . '"');
            }
        });

        if( Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) ) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $aValidate = ActiveForm::validate($model);
//            Yii::info('addDoclad(): return json ' . print_r($aRes, true));
            return $aValidate;
        }

        if( $model->load(Yii::$app->request->post()) && $model->validate() ) {
            if( $model->doc_state == Doclad::DOC_STATUS_APPROVE ) {
                $model->doc_comment = '';
            }

            if( $model->save() ) {
                Yii::$app->session->setFlash('success', 'Данные успешно сохранены');
                return $this->redirect(['index']);
            }
            else {
                Yii::info('Error save: ' . print_r($model->getErrors(), true));
            }
        }

        return $this->render('fullview', [
            'model' => $model,
        ]);
    }

    /**
     * Get statistics
     */
    public function actionStatistics()
    {
        return $this->render(
            'statistics',
            [
                'data' => Statistics::getConferenceStat(),
            ]
        );
//        return $this->renderContent(nl2br(str_replace(' ', '&nbsp;', print_r($a, true))));
    }

    /**
     * Finds the Doclad model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Doclad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findDoclad($id)
    {
        if (($model = Doclad::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
