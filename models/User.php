<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\IdentityInterface;

use app\components\Notificator;
use app\components\ActionBehavior;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $us_id
 * @property string $us_group
 * @property integer $us_active
 * @property string $us_email
 * @property string $us_pass
 * @property string $us_created
 * @property string $us_confirmkey
 * @property string $us_key
 * @property string $us_conference_id
 */
class User extends \yii\db\ActiveRecord  implements IdentityInterface
{
    const USER_GROUP_PERSONAL = 'person';
    const USER_GROUP_ORGANIZATION = 'org';
    const USER_GROUP_MODERATOR = 'mod';

    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;

    public $password = '';

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['us_created'],
                ],
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'password',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'password',
                ],
                'value' => function ($event) {
                    /** @var \yii\base\Event $event */
                    $model = $event->sender;
                    if( !empty($model->password) ) {
                        $model->setPassword($model->password);
                    }
                    return $model->password;
                },
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'us_confirmkey',
                ],
                'value' => function ($event) {
                    /** @var \yii\base\Event $event */
                    return Yii::$app->security->generateRandomString() . time();
                },
            ],
            [
                'class' => ActionBehavior::className(),
                'allevents' => [ActiveRecord::EVENT_AFTER_INSERT],
                'action' => function($event) {
                    /** @var \yii\base\Event $event */
                    $model = $event->sender;
                    if( $model->scenario == 'register' ) {
                        $oNotify = new Notificator([$model], $model, 'confirm_mail');
                        $oNotify->notifyMail('Подтвердите регистрацию на портале "' . Yii::$app->name . '"');
                    }
                }
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['us_email', 'us_group', ], 'required'],
            [['us_group'], 'in', 'range' => array_keys(self::getRegGroups()), ],
            [['us_active', 'us_conference_id', ], 'integer'],
            [['us_created'], 'safe'],
            [['us_group'], 'string', 'max' => 16],
            [['us_email'], 'string', 'max' => 64],
            [['us_pass', 'us_confirmkey', 'us_key'], 'string', 'max' => 255],
            [['password'], 'required'],
            [['password'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'us_id' => 'Us ID',
            'us_group' => 'Группа',
            'us_active' => 'Активен',
            'us_email' => 'Электронная почта',
            'us_pass' => 'Пароль',
            'password' => 'Пароль',
            'us_created' => 'Создан',
            'us_confirmkey' => 'Confirm key',
            'us_key' => 'API key',
            'us_conference_id' => 'Конференция регистрации',
        ];
    }

    /**
     * Сценарии изменения пользователей
     * @return array
     */
    public function scenarios() {
        $aRet = parent::scenarios();
        $aRet['register'] = [ // пользователи сами регистрируются
            'us_email',
            'password',
            'us_group',
        ];

        $aRet['confirmregister'] = [ // подтвкрждение регистрации
            'us_active',
        ];
        return $aRet;
    }

    /**
     * Список всех групп пользователей
     * @return array
     */
    public static function getAllGroups() {
        return [
            self::USER_GROUP_PERSONAL => 'Обучающийся',
            self::USER_GROUP_ORGANIZATION => 'Представитель образовательной организации',
            self::USER_GROUP_MODERATOR => 'Модератор',
        ];
    }

    /**
     * Список всех групп пользователей, которыми можно регистрироваться
     * @return array
     */
    public static function getRegGroups() {
        $a = self::getAllGroups();

        unset($a[self::USER_GROUP_MODERATOR]);

        return $a;
    }

// ********************************************************************************************
    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->us_pass = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
//        return self::findOne($id);
        return static::find()
            ->where([
                'us_id' => $id,
                'us_active' => self::STATUS_ACTIVE,
//                'us_group' => [self::GROUP_ADMIN, self::GROUP_OPERATOR, self::GROUP_CLIENT, ],
            ])
            ->one();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['us_key' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::find()
            ->where([
                'us_email' => $username,
                'us_active' => self::STATUS_ACTIVE,
//                'us_group' => [self::GROUP_ADMIN, self::GROUP_OPERATOR, self::GROUP_CLIENT, ],
            ])
            ->one();
//        return self::findOne(['us_email' => $username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->us_id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->us_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->us_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
//        return $this->password === $password;
        return Yii::$app->security->validatePassword($password, $this->us_pass);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->us_key = Yii::$app->security->generateRandomString();
    }// ********************************************************************************************

}
