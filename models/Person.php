<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

use app\components\Notificator;
use app\components\ActionBehavior;
use app\components\RustextValidator;
use app\models\Section;
use app\models\Doclad;

/**
 * This is the model class for table "{{%person}}".
 *
 * @property integer $prs_id
 * @property integer $prs_active
 * @property integer $prs_type
 * @property string $prs_fam
 * @property string $prs_name
 * @property string $prs_otch
 * @property string $prs_email
 * @property string $prs_phone
 * @property integer $prs_sec_id
 * @property integer $prs_doc_id
 * @property integer $ekis_id
 * @property string $prs_org
 * @property string $prs_group
 * @property string $prs_position
 * @property string $prs_lesson
 * @property string $prs_created
 * @property string $prs_confirmkey
 * @property integer $prs_agree_pers
 * @property string $prs_hischool
 */
class Person extends \yii\db\ActiveRecord
{
    const PERSON_TYPE_GUEST = 1;
    const PERSON_TYPE_CONSULTANT = 2;
    const PERSON_TYPE_STUD_MEMBER = 3;
    const PERSON_TYPE_ORG_MEMBER = 4;

    const PERSON_STATE_ACTIVE = 1;
    const PERSON_STATE_NONACTIVE = 0;

    public $aSectionList = [];

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['prs_created'],
                ],
                'value' => new Expression('NOW()'),
            ],
            [
                // для гостя генерим ключик для проверки почты через отправку письма
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'prs_confirmkey',
                ],
                'value' => function ($event) {
                    /** @var \yii\base\Event $event */
                    /** @var Person $model */
                    $model = $event->sender;
                    if( $model->prs_type == Person::PERSON_TYPE_GUEST ) {
                        return Yii::$app->security->generateRandomString() . time();
                    }
                    return $model->prs_confirmkey;
                },
            ],
            [
                'class' => ActionBehavior::className(),
                'allevents' => [ActiveRecord::EVENT_AFTER_INSERT],
                'action' => function($event) {
                    /** @var \yii\base\Event $event */
                    /** @var Person $model */
                    /** @var \\app\\components\Notificator $oNotify */
                    $model = $event->sender;
                    if( $model->prs_type == Person::PERSON_TYPE_GUEST ) {
                        $oNotify = new Notificator([$model], $model, 'confirm_guest_mail');
                        $oNotify->sEmailField = 'prs_email';
                        $oNotify->notifyMail('Подтвердите регистрацию в качестве гостя на портале "' . Yii::$app->name . '"');
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
        return '{{%person}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $a = [
//            [['prs_type', ], 'filter', 'filter'=>[$this, 'setPersonType'], ],
            [['prs_active', 'prs_type', 'prs_sec_id', 'prs_doc_id', 'ekis_id', 'prs_agree_pers', ], 'integer'],
            [['prs_agree_pers', ], 'in', 'range' => [1], 'message' => 'Необходимо дать разрешение на обработку персональных данных'],
            [['prs_sec_id', ], 'in', 'range' => array_keys($this->aSectionList)],
            [['prs_type', 'prs_fam', 'prs_name', 'prs_otch', 'prs_email', 'prs_position', 'prs_lesson', 'prs_group', 'prs_position', 'prs_lesson', 'prs_agree_pers', ], 'required'],
//            [['prs_fam', 'prs_name', 'prs_otch', 'prs_position', 'prs_lesson', ], RustextValidator::className(), 'capital' => 0, 'russian' => 0.8, 'other'=>0, ],
            [['prs_fam', 'prs_name', 'prs_otch', ], 'match',
                'pattern' => '|^[А-Яа-яЁё]{2}[-А-Яа-яЁё\\s]*$|u', 'message' => 'Допустимы символы русского алфавита',
            ], // 'prs_position', 'prs_lesson',

//            [['ekis_id', ], 'integer'],

            [['prs_email', ], 'email', ],
            [['prs_email', ], 'unique',
                'targetAttribute' => ['prs_email', 'prs_type', 'prs_active', 'prs_sec_id', ],
                'when' => function ($model) {return $model->prs_type == self::PERSON_TYPE_GUEST; },
            ],

            [['prs_created', ], 'save', ],

            [['prs_fam', 'prs_name', 'prs_otch', 'prs_org', 'prs_confirmkey', ], 'string', 'max' => 255],
            [['prs_hischool'], 'string', 'max' => 255],
            [['prs_email'], 'string', 'max' => 128],
            [['prs_phone'], 'string', 'max' => 24],
            [['prs_group', 'prs_position', 'prs_lesson'], 'string', 'max' => 64]
        ];

        if( ($this->scenario != 'createconsultant') && ($this->scenario != 'createguest') ) {
            $a[] = [['ekis_id', ], 'required'];
        }
        else {
            $a[] = [
                ['ekis_id', ],
                'required',
                'when' => function ($model) {
                    $bEmpty = empty($model->prs_hischool);
                    // todo
                    // тут очень плохая вещь - чтобы не менять вывод потом - присваиваем названию организации
                    if( !$bEmpty ) {
                        if( empty($model->ekis_id) ) {
                            $model->prs_org = $model->prs_hischool;
                        }
                    }
                    return $bEmpty;
                },
                'message' => 'Нужно указать или организацию, или ВУЗ' . ($this->scenario == 'createconsultant' ? ' руководителя' : ''),
            ];

        }
//        Yii::info(self::className() . '::validators a = ' . print_r($a, true));

        return $a;
    }

    /**
     * Сценарии
     * @return array
     */
    public function scenarios() {
        if( $this->prs_type == self::PERSON_TYPE_CONSULTANT ) {
            $this->scenario = 'createconsultant';
        }
        else if( $this->prs_type == self::PERSON_TYPE_STUD_MEMBER ) {
            $this->scenario = 'createmember';
        }
        else if( $this->prs_type == self::PERSON_TYPE_ORG_MEMBER ) {
            $this->scenario = 'createorgmember';
        }
        else if( $this->prs_type == self::PERSON_TYPE_GUEST ) {
            $this->scenario = $this->isNewRecord ? 'createguest' : $this->scenario;
        }
        $aRet = parent::scenarios();

//        Yii::trace(self::className() . ': scenarios(): ' . $this->scenario);
        $aRet['createconsultant'] = [ // руководитель
            'prs_doc_id',
            'prs_fam',
            'prs_name',
            'prs_otch',
            'ekis_id',
            'prs_org',
            'prs_email',
            'prs_position',
            'prs_lesson',
            'prs_agree_pers',
            'prs_hischool',
        ];

        $aRet['createmember'] = [ // соучастник персональный
            'prs_doc_id',
            'prs_fam',
            'prs_name',
            'prs_otch',
            'ekis_id',
            'prs_org',
            'prs_group',
//            'prs_level',
            'prs_agree_pers',
        ];

        $aRet['createorgmember'] = [ // соучастник от организации
            'prs_doc_id',
            'prs_fam',
            'prs_name',
            'prs_otch',
            'ekis_id',
            'prs_org',
            'prs_position',
            'prs_lesson',
            'prs_agree_pers',
        ];

        $aRet['createguest'] = [ // гость
            'prs_fam',
            'prs_name',
            'prs_otch',
            'prs_email',
            'prs_sec_id',
            'ekis_id',
            'prs_org',
            'prs_position',
            'prs_hischool',
        ];

        $aRet['confirmemail'] = [ // подтвeрждение регистрации
            'prs_active',
        ];
        return $aRet;
    }



    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'prs_id' => 'Prs ID',
            'prs_active' => 'Активен',
            'prs_type' => 'Тип участника',
            'prs_fam' => 'Фамилия',
            'prs_name' => 'Имя',
            'prs_otch' => 'Отчество',
            'prs_email' => 'Email',
            'prs_phone' => 'Телефон',
            'prs_sec_id' => 'Секция',
            'prs_doc_id' => 'Доклад',
            'ekis_id' => 'Организация',
            'prs_org' => 'Организация',
            'prs_group' => 'Класс / Курс',
//            'prs_level' => 'Курс',
            'prs_position' => 'Должность',
            'prs_lesson' => 'Предмет',
            'prs_hischool' => 'ВУЗ',
            'prs_created' => 'Дата регистрации',
            'prs_confirmkey' => 'Ключик проверки email',
            'conferenceid' => 'Конференция',
            'prs_agree_pers' => 'Подтверждаю согласие на обработку персональных данных',
        ];
    }

    /**
     * Все типы персон доклада
     * @return array
     */
    public static function getAllTypes() {
        return [
            self::PERSON_TYPE_CONSULTANT => 'Руководитель',
            self::PERSON_TYPE_GUEST => 'Гость',
            self::PERSON_TYPE_STUD_MEMBER => 'Соучастник',
            self::PERSON_TYPE_ORG_MEMBER => 'Соучастник',
        ];

    }

    /**
     * Название типа персоны
     *
     * @return string|null
     */
    public function getTypename() {
        $a = self::getAllTypes();
        return isset($a[$this->prs_type]) ? $a[$this->prs_type] : null;
    }

    /**
     * Отношение к докладу
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDoclad() {
        return $this->hasOne(Doclad::className(), ['doc_id' => 'prs_doc_id']);
    }

    /**
     * Отношение к секции
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSection() {
        return $this->hasOne(Section::className(), ['sec_id' => 'prs_sec_id']);
    }

    /**
     * Получение имени персоны
     *
     * @param bool $bShort
     * @return string
     */
    public function getPersonname($bShort = true) {
        return $bShort ?
            ($this->prs_name . ' ' . $this->prs_otch) :
            ($this->prs_fam . ' ' . $this->prs_name . ' ' . $this->prs_otch);
    }
}
