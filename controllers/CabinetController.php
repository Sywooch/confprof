<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

use app\models\Conference;
use app\controllers\BaseConferenceController;
use app\models\DocladSearch;


class CabinetController extends BaseConferenceController
{
    public $conferenceId = 0;
    public $layout = 'frontend_cabinet';

    public function behaviors()
    {
        $a = parent::behaviors();
        $a['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index', 'list', 'create', 'view', 'update', 'logout', 'delete', 'deletefile', ],
                    'roles' => ['@'],
                ],
            ],
        ];
        return $a;
    }

    /**
     * @return string
     */
    public function actionIndex() {
        if( Yii::$app->user->can(User::USER_GROUP_MODERATOR) ) {
            return $this->redirect(['admin/report']);
        }
        return parent::actionList();
//        $aDop = ['doc_us_id' => Yii::$app->user->getId()];
//        $searchModel = new DocladSearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $aDop);
//
//        return $this->render(
//            '//doclad/userdoclist', [
//                'searchModel' => $searchModel,
//                'dataProvider' => $dataProvider,
//            ]
//        );
    }

    /**
     * @return \yii\web\Response
     */
    public function actionList() {
        return $this->redirect(['index']);
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $oConf = $model->section ? $model->section->conference : null;
        if( $oConf === null ) {
            throw new NotFoundHttpException('Не найдена информация о конференции');
        }
        $this->conferenceId = $oConf->cnf_id;
        return parent::actionUpdate($id);
    }

    /**
     * @param int $id
     */
    public function actionCreate($id = 0) {
        if( $id != 0 ) {
            $oConf = Conference::findOne($id);
            if( $oConf === null ) {
                $id = 0;
            }
        }

        if( $id == 0 ) {
            return $this->render(
                '//doclad/select_conference',
                [
                    'link' => ['cabinet/create', 'id' => 0],
                    'partial' => false,
                ]
            );
        }
        else {
            $this->conferenceId = $oConf->cnf_id;
            return parent::addDoclad();
        }
    }
}
