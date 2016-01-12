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

use app\components\Notificator;
use app\models\User;
use app\models\Doclad;
use app\models\DocladSearch;
use app\models\Person;


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
                        'actions' => ['index', 'view', ],
                        'roles' => [User::USER_GROUP_MODERATOR, ],
                    ],
                ],
            ],

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
     * Show doclad info
     * @param $id integer
     */
    public function actionView($id)
    {
        $model = $this->findDoclad($id);
        $model->_oldAttributesValues = $model->attributes;
        $model->on(ActiveRecord::EVENT_AFTER_UPDATE, function ($event) {
            /** @var Doclad $model */
            $model = $event->sender;
            if( $model->_oldAttributesValues['doc_state'] != $model->doc_state ) {
                $oNotify = new Notificator([User::findOne($model->doc_us_id)], $model, 'change_doclad_state');
                $oNotify->notifyMail('Можератор изменил статус Вашего доклада на сайте "' . Yii::$app->name . '"');
            }
        });


        $model->scenario = 'changestatus';

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
