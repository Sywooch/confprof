<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\InvalidCallException;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\widgets\ActiveForm;
use mosedu\multirows\MultirowsBehavior;
use yii\helpers\Html;

use app\models\UsersectionForm;
use app\models\User;
use app\models\UserSearch;
use app\models\Conference;
use app\models\NewpasswordForm;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
//                'only' => ['index', 'update', 'create',  'view', 'export', ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'update', 'create',  'view', 'export', ],
                        'roles' => [User::USER_GROUP_ADMIN],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['confirmemail', 'setnewpassword', ],
                        'roles' => ['?', ],
                    ],
                ],
            ],

            'validateSections' => [
                'class' => MultirowsBehavior::className(),
                'model' => UsersectionForm::className(),
            ],

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->us_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @param User $model
     * @return array
     */
    public function validateModel($model) {
        $aValidate = ActiveForm::validate($model);

        $result = $this->getBehavior('validateSections')->validateData();
        $data = $this->getBehavior('validateSections')->getData();
        if( ($model->us_group == User::USER_GROUP_MODERATOR) && count($data['data']) == 0 ) {
            $sId = Html::getInputId($model, 'us_email');

            if( !isset($aValidate[$sId]) ) {
                $aValidate[$sId] = [];
            }
            $aValidate[$sId][] = 'Необходимо указать секции.';
        }
        $aRes = array_merge($aValidate, $result);
        return $aRes;
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $aRes = $this->validateModel($model);

            Yii::trace(self::className() . 'actionUpdate(): return json ' . print_r($aRes, true));
            return $aRes;
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $aRes = $this->validateModel($model);
            if( (count($aRes) > 0) || !$model->save() ) {
                Yii::info('Error save user: ' . print_r($aRes, true) . ' attributes: ' . print_r($model->attributes, true));
            }
            else {
                $data = $this->getBehavior('validateSections')->getData();
                Yii::trace('data[data] = ' . print_r($data['data'], true));
                if( $model->us_group == User::USER_GROUP_MODERATOR ) {
                    $model->saveSectionsWithPrimary($data['data']);
                }
                return $this->redirect(['index', ]);
            }
//            return $this->redirect(['view', 'id' => $model->us_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
//        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Confirm user registration
     * @return mixed
     */
    public function actionConfirmemail($key = '')
    {
        $this->layout = 'frontend01';
        if( $key == '' ) {
            throw new InvalidCallException('Не указан ключ');
        }

        $model = User::findOne(['us_confirmkey' => $key]);

        if( $model !== null ) {
            $model->us_active = 1;
            $model->scenario = 'confirmregister';
            $model->us_confirmkey = '';
            if( $model->save() ) {
                $oConf = Conference::getById($model->us_conference_id, 1);
                if( $oConf === null ) {
                    throw new NotFoundHttpException('Не найдена конференция для регистрации');
                }
                return $this->redirect([$oConf->cnf_controller . '/confirm', 'id' => $model->us_id]);
            }
            else {
                Yii::info('Error save User Confirmemail: ' . print_r($model->getErrors(), true));
                throw new NotFoundHttpException('Ошибка подтверждения регистрации' . print_r($model->getErrors(), true));
            }
        }
        throw new NotFoundHttpException('Ошибка подтверждения регистрации - не найдена требуемая информация');
        return '';
    }

    /**
     * Set new password
     * @return mixed
     */
    public function actionSetnewpassword($key = '')
    {
//        $this->layout = 'frontend01';
        if( $key == '' ) {
            throw new InvalidCallException('Не указан ключ');
        }

        /** @var User $oUser */
        $oUser = User::findOne(['us_key' => $key]);

        if( $oUser !== null ) {
            $model = new NewpasswordForm();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                $oUser->password = $model->password;
                if( $oUser->save() ) {
                    Yii::$app->session->setFlash('success', 'Вы установили новый пароль.');
                }
                else {
                    Yii::$app->session->setFlash('danger', 'Ошибка сохранения пароля.');
                    Yii::info('Error set new password: ' . print_r($oUser->getErrors(), true) . ' attributes: ' . print_r($oUser->attributes, true));
                }

                return $this->refresh();
//            return $this->redirect('/');
            }

            return $this->render('newpassword', [
                'model' => $model,
            ]);

        }
        throw new NotFoundHttpException('Ошибка установки пароля - не найдена требуемая информация');
        return '';
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}
