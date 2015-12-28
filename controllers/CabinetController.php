<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;

use app\models\Conference;
use app\controllers\BaseConferenceController;
use app\models\DocladSearch;


class CabinetController extends BaseConferenceController
{
    public $conferenceId = 0;
    public $layout = 'frontend_cabinet';


    /**
     * @return string
     */
    public function actionIndex() {
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
     * @return \yii\web\Response
     */
    public function actionList() {
        return $this->redirect(['index']);
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $oConf = $model->section ? $model->section->conference : null;
        if( $oConf === null ) {
            throw new NotFoundHttpException('Не найдена информация о конференции');
        }
        $this->conferenceId = $oConf->cnf_id;
        return parent::actionUpdate($id);
    }
}
