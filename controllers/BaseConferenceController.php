<?php

namespace app\controllers;

use Yii;
use app\models\Conference;
use app\models\ConferenceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ConferenceController implements the CRUD actions for Conference model.
 */
class BaseConferenceController extends Controller
{
    public $conferenceId = null;

    public $layout = 'frontend_conference';

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
        return $this->render('register', []);
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
