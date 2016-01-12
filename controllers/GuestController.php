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

use app\models\User;
use app\models\PersonSearch;
use app\models\Person;

/**
 * ConferenceController implements the CRUD actions for Conference model.
 */
class GuestController extends Controller
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
        $searchModel = new PersonSearch();
        $dataProvider = $searchModel->search(
            Yii::$app->request->queryParams,
            [
                'prs_type' => Person::PERSON_TYPE_GUEST,
                'prs_active' => 1,
            ]
        );

        return $this->render(
            'guestindex',
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
        $model = $this->findGuest($id);
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
     * @return Person the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findGuest($id)
    {
        if( ($model = Person::find(['prs_id' => $id, 'prs_type' => Person::PERSON_TYPE_GUEST, ])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
