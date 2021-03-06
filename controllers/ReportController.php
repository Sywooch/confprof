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
use app\models\Usersection;
use app\models\Section;


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
                        'actions' => ['index', 'view', 'changestatus', 'changeformat', 'export', 'statistics', 'exportall', ],
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
                    'Описание',
                    'Создан',
                    'Участники',
                    'Руководители',
                    'Файл',
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
                    60,
                    20,
                    30,
                    30,
                    20,
                ],
                'columnValues' => [
                    'doc_subject',
                    function($model, $index) {
                        /** @var Doclad $model */
//                        Yii::info('Raw: ' . $index . ' + ' . $model->doc_subject);
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
                            ($model->doc_lider_group) : //  . ' / ' . $model->doc_lider_level
                            ($model->doc_lider_position . ' / ' . $model->doc_lider_lesson) ;
                    },
                    'status',
                    'format',
                    'doc_description',
                    function($model, $index) {
                        /** @var Doclad $model */
                        return date('d.m.Y H:i', strtotime($model->doc_created));
                    },
                    function($model, $index) {
                        /** @var Doclad $model */
                        $sValue = '';
                        if( count($model->members) > 0 ) {
                            $sValue = implode(
                                "\n",
                                ArrayHelper::map(
                                    $model->members,
                                    'prs_id',
                                    function($ob, $default) {
                                        /** @var $ob app\models\Person */
                                        return $ob->getPersonname(false) . ' ' . $ob->prs_org;
                                    }
                                )
                            );
                        }
                        return $sValue;
                    },
                    function($model, $index) {
                        /** @var Doclad $model */
                        $sValue = '';
                        if( count($model->persons) > 0 ) {
                            $sValue = implode(
                                "\n",
                                ArrayHelper::map(
                                    $model->persons,
                                    'prs_id',
                                    function($ob, $default) {
                                        /** @var $ob app\models\Person */
                                        return $ob->getPersonname(false)  . ' ' . $ob->prs_email . ' ' . $ob->prs_org;
                                    }
                                )
                            );
                        }
                        return $sValue;
                    },
                    function($model, $index) {
                        /** @var Doclad $model */
                        $sValue = '';
                        $aFiles = $model->files;
                        if( count($aFiles) > 0 ) {
                            $sValue = array_reduce(
                                $aFiles,
                                function($sRes, $item){
                                    /** @var File $item */
                                    $s = basename(str_replace('\\', '/', $item->file_name));
                                    return $s
                                    . ($sRes != '' ? "\n" : '')
                                    . $sRes;
                                },
                                ''
                            );
                        }
                        return $sValue;
                    },
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
        $aWith = [
            'with' => ['persons', 'members', ],
        ];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $aWith);

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
     * Export data without division to section
     * @return mixed
     */
    public function actionExportall()
    {
        $searchModel = new DocladSearch();

        $searchModel->conferenceid = Yii::$app
            ->db
            ->createCommand(
                'Select s.sec_cnf_id'
                . ' From ' . Usersection::tableName() . ' us, ' . Section::tableName() . ' s'
                . ' Where s.sec_id = us.usec_section_id And us.usec_section_primary = 1 And us.usec_user_id = :uid',
                [
                    ':uid' => Yii::$app->user->getId()
                ]
            )
            ->queryColumn();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//        echo nl2br(print_r($searchModel->conferenceid, true));
//        return;

        $sDir = Yii::getAlias('@webroot/assets');
        $sFileName = $sDir . DIRECTORY_SEPARATOR . 'doclad-all-'.date('Y-m-d-H-i-s').'.xls';
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
