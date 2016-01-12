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
use app\models\File;

/**
 * ConferenceController implements the CRUD actions for Conference model.
 */
class ReportController extends Controller
{
    public function behaviors()
    {
        return [
//            'validateConsultant' => [
//                'class' => MultirowsBehavior::className(),
//                'model' => Person::className(),
//            ],
//            'validateMedals' => [
//                'class' => MultirowsBehavior::className(),
//                'model' => Docmedal::className(),
//            ],
//            'validateMembers' => [
//                'class' => MultirowsBehavior::className(),
//                'model' => Member::className(),
//            ],

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
        return $this->render('fullview', [
//            'conference' => $this->findConferenceModel(),
            'model' => $model,
        ]);

    }

    /**
     * @return string
     */
    public function actionList() {
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
