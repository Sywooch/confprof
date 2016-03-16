<?php

namespace app\models;

use Yii;

use yii\helpers\Html;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;

use app\models\Person;
use app\models\Member;
use app\models\Docmedal;
use app\models\Section;
use app\models\File;
use app\components\FilesaveBehavior;
use app\components\RustextValidator;
use app\models\Doctalk;

/**
 * This is the model class for table "{{%doclad}}".
 *
 * @property integer $doc_id
 * @property integer $doc_us_id
 * @property integer $doc_sec_id
 * @property string $doc_type
 * @property string $doc_subject
 * @property string $doc_description
 * @property string $doc_created
 * @property string $doc_lider_fam
 * @property string $doc_lider_name
 * @property string $doc_lider_otch
 * @property string $doc_lider_email
 * @property string $doc_lider_phone
 * @property integer $ekis_id
 * @property string $doc_lider_org
 * @property string $doc_lider_group
 * @property string $doc_lider_position
 * @property string $doc_lider_lesson
 * @property integer $doc_state
 * @property string $doc_comment
 * @property integer $doc_format
 * @property integer $doc_agree_pers
 */
class Doclad extends \yii\db\ActiveRecord
{
    const DOC_TYPE_PERSONAL = 'person';
    const DOC_TYPE_ORG = 'org';

    const DOC_STATUS_NEW = 0;
    const DOC_STATUS_APPROVE = 1;
    const DOC_STATUS_NOT_APPROVE = 2;
    const DOC_STATUS_REVISION = 3;

    const DOC_FORMAT_NOFORMAT = 0;
    const DOC_FORMAT_STAND = 1;
    const DOC_FORMAT_TALK = 2;
    const DOC_FORMAT_PUBLICATION = 3;

    public $aSectionList = [];

    public $file = null;

    public $conferenceid = null;

    public $_oldAttributesValues = null;

    /**
     * @return array
     */
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['doc_created'],
                ],
                'value' => new Expression('NOW()'),
            ],
            'fileSave' => [
                'class' => FilesaveBehavior::className(),
                'filesaveFileModel' => 'app\models\File',
                'filesaveConvertFields' => [
                    'file_orig_name' => 'name',
                    'file_size' => 'size',
                    'file_type' => 'type',
                    'file_name' => 'fullpath',
                    'file_doc_id' => 'parentid',
                    'file_us_id' => Yii::$app->user->getId(),
                ],
                'filesaveBaseDirName' => '@webroot/images/doclad'
            ],

        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%doclad}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['doc_sec_id', 'ekis_id', 'doc_us_id', 'conferenceid', 'doc_state', 'doc_format', 'doc_agree_pers', 'doc_work_original', ], 'integer'],
            [['doc_agree_pers', ], 'in', 'range' => [1], 'message' => 'Необходимо дать разрешение на обработку персональных данных'],
            [['doc_work_original', ], 'in', 'range' => [1], 'message' => 'Необходимо подтвердить соответствие требованиям оригинальности'],
            [['doc_sec_id', ], 'in', 'range' => array_keys($this->aSectionList), ],
            [['doc_format', ], 'in', 'range' => array_keys($this->getAllFormats()), ],
            [['doc_type', 'doc_sec_id', 'doc_subject', 'doc_description', 'doc_lider_fam', 'doc_lider_name', 'doc_lider_otch', 'doc_lider_email', 'doc_lider_phone', 'ekis_id', 'doc_lider_group', 'doc_lider_position', 'doc_lider_lesson', 'doc_state', 'doc_agree_pers', 'doc_work_original', ], 'required'],
            [['doc_lider_fam', 'doc_lider_name', 'doc_lider_otch', 'doc_lider_position', 'doc_lider_lesson', ], 'filter', 'filter' => 'trim', ],
            [['doc_description'], 'string', 'min' => 32, 'max' => Yii::$app->params['doclad.description.maxlength'], ],
            [['doc_lider_email'], 'email', ],
            [['doc_created'], 'safe'],
//            [['doc_lider_fam', 'doc_lider_name', 'doc_lider_otch', 'doc_lider_position', 'doc_lider_lesson', ], RustextValidator::className(), 'capital' => 0, 'russian' => 0.8, 'other'=>0, ],
            [['doc_lider_fam', 'doc_lider_name', 'doc_lider_otch', 'doc_lider_position', 'doc_lider_lesson', ], 'match',
                'pattern' => '|^[А-Яа-яЁё]{2}[-А-Яа-яЁё\\s]*$|u', 'message' => 'Допустимы символы русского алфавита',
            ],
            [['doc_type'], 'string', 'max' => 16],
            [['doc_comment'], 'string', ],
            [['doc_comment'], 'required', 'when' => function($model){ return in_array($model->doc_state, [self::DOC_STATUS_NOT_APPROVE, self::DOC_STATUS_REVISION, ]); }, ],
            [['doc_type'], 'in', 'range' => array_keys(self::getAllTypes())],
            [['doc_subject', 'doc_lider_fam', 'doc_lider_name', 'doc_lider_otch', 'doc_lider_email', 'doc_lider_phone', 'doc_lider_org', 'doc_lider_group', 'doc_lider_position', 'doc_lider_lesson'], 'string', 'max' => 255],
            [['file'], 'safe'],
            [['file'], 'file', 'maxFiles' => 1, 'maxSize' => Yii::$app->params['doclad.file.maxsize'], 'extensions' => Yii::$app->params['doclad.file.ext']],
        ];
    }

    /**
     * Сценарии изменения
     * @return array
     */
    public function scenarios() {
        $aRet = parent::scenarios();
        $aRet['create'] = [ // пользователи сами регистрируются
            'doc_sec_id',
            'doc_us_id',
            'doc_type',
            'doc_subject',
            'doc_description',
            'doc_created',
            'doc_lider_fam',
            'doc_lider_name',
            'doc_lider_otch',
            'doc_lider_email',
            'doc_lider_phone',
            'ekis_id',
            'doc_lider_org',
            'doc_agree_pers',
            'doc_work_original',
        ];

        if($this->doc_type == self::DOC_TYPE_ORG) {
            $aRet['create'] = array_merge(
                $aRet['create'],
                [
                    'doc_lider_position',
                    'doc_lider_lesson',
                ]
            );
        }
        else {
            $aRet['create'] = array_merge(
                $aRet['create'],
                [
                    'doc_lider_group',
//                    'doc_lider_level',
                ]
            );
        }

        $aRet['changestatus'] = [ // меняем статус
            'doc_comment',
            'doc_state',
        ];

        $aRet['changeformat'] = [ // меняем формат вытупления
            'doc_format',
        ];
        return $aRet;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'doc_id' => 'Doc ID',
            'doc_sec_id' => 'Секция',
            'doc_us_id' => 'Пользователь',
            'doc_type' => 'Тип доклада',
            'doc_subject' => 'Тема',
            'doc_description' => 'Описание работы',
            'doc_created' => 'Создан',
            'doc_lider_fam' => 'Фамилия',
            'doc_lider_name' => 'Имя',
            'doc_lider_otch' => 'Отчество',
            'doc_lider_email' => 'Email',
            'doc_lider_phone' => 'Телефон',
            'ekis_id' => 'Организация',
            'doc_lider_org' => 'Организация',
            'doc_lider_group' => 'Класс / Курс',
//            'doc_lider_level' => 'Курс',
            'doc_lider_position' => 'Должность',
            'doc_lider_lesson' => 'Предмет',
            'members' => 'Участники',
            'consultants' => 'Руководители',
            'file' => 'Файл',
            'conferenceid' => 'Конференция',
            'doc_state' => 'Статус',
            'status' => 'Статус',
            'doc_comment' => 'Комментарий',
            'doc_format' => 'Формат',
            'format' => 'Формат выступления',
            'doc_agree_pers' => 'Подтверждаю согласие на обработку персональных данных',
            'doc_work_original' => 'Предоставляемая работа соответствует требованиям оригинальности',
        ];
    }

    /**
     * Получаем список форматов выступления
     * @return array
     */
    public static function getAllFormats() {
        return [
            self::DOC_FORMAT_NOFORMAT => 'Не определен',
            self::DOC_FORMAT_PUBLICATION => 'Публикация',
            self::DOC_FORMAT_TALK => 'Выступление',
            self::DOC_FORMAT_STAND => 'Стендовый доклад',
        ];
    }

    /**
     * Текущий формат
     * @return array
     */
    public function getFormat() {
        $a = self::getAllFormats();
        return isset($a[$this->doc_format]) ? $a[$this->doc_format] : '???';
    }

    /**
     * Получаем список статусов
     * @return array
     */
    public static function getAllStatuses() {
        return [
            self::DOC_STATUS_NEW => 'Новый',
            self::DOC_STATUS_APPROVE => 'Согласован',
            self::DOC_STATUS_NOT_APPROVE => 'Несогласован',
            self::DOC_STATUS_REVISION => 'На доработку',
        ];
    }

    /**
     * Текущий статус
     * @return array
     */
    public function getStatus() {
        $a = self::getAllStatuses();
        return isset($a[$this->doc_state]) ? $a[$this->doc_state] : '???';
    }

    /**
     * Получаем список докладов по условию
     * @param array $aWhere условие
     * @return array
     */
    public static function getAllList($aWhere = []) {
        return self::find()->where($aWhere)->all();
    }

    /**
     * Список типов для проверок и списков
     * @return array
     */
    public static function getAllTypes() {
        return [
            self::DOC_TYPE_PERSONAL => 'Персональный',
            self::DOC_TYPE_ORG => 'От организации',
        ];
    }

    /**
     * Установка типа доклада
     * @param string $sType
     */
    public function setDocType($sType = '') {
        if( in_array($sType, array_keys(self::getAllTypes())) ) {
            $this->doc_type = $sType;
        }
    }

    /**
     * Название типа доклада - персональный или от организации
     * @return string|null
     */
    public function typeTitle() {
        $a = self::getAllTypes();
        return isset($a[$this->doc_type]) ? $a[$this->doc_type] : null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersons() {
        return $this->hasMany(Person::className(), ['prs_doc_id' => 'doc_id'])->where(['prs_type' => Person::PERSON_TYPE_CONSULTANT]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembers() {
        return $this->hasMany(Member::className(), ['prs_doc_id' => 'doc_id'])->where(['prs_type' => [Person::PERSON_TYPE_ORG_MEMBER, Person::PERSON_TYPE_STUD_MEMBER]]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedals() {
        return $this->hasMany(Docmedal::className(), ['mdl_doc_id' => 'doc_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSection() {
        return $this->hasOne(Section::className(), ['sec_id' => 'doc_sec_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFiles() {
        return $this->hasMany(File::className(), ['file_doc_id' => 'doc_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTalks() {
        return $this->hasMany(Doctalk::className(), ['dtlk_doc_id' => 'doc_id']);
    }

    /**
     * @param $data
     */
    public function saveMembers($data)
    {
        return $this->savePersons($data, $this->doc_type == self::DOC_TYPE_ORG ? Person::PERSON_TYPE_ORG_MEMBER : Person::PERSON_TYPE_STUD_MEMBER);
    }

    /**
     * @param $data
     */
    public function saveConsultants($data) {
        return $this->savePersons($data, Person::PERSON_TYPE_CONSULTANT);
    }

    /**
     * Сохраняем участников или консультантов
     *
     * @param $data array
     * @param $nType integer
     */
    public function savePersons($data, $nType) {
        $bOk = true;
        Person::updateAll(
            [
                'prs_active' => 0,
                'prs_type' => 0,
                'prs_sec_id' => 0,
                'prs_doc_id' => 0,
            ],
            [
                'prs_doc_id' => $this->doc_id,
                'prs_type' => $nType,
            ]
        );

        foreach($data as $ob) {
            $ob['prs_type'] = $nType;
            $ob['prs_doc_id'] = $this->doc_id;
            $ob['prs_active'] = Person::PERSON_STATE_ACTIVE;
            $ob['prs_sec_id'] = $this->doc_sec_id;

//            Yii::info('consultant = ' . print_r($ob, true));

            $s = 'Update ' . Person::tableName() . ' Set ';
            $param = [];
            $sDelim = '';
            if( empty($ob['prs_org']) && !empty($ob['prs_hischool']) ) {
                $ob['prs_org'] = $ob['prs_hischool'];
            }

            foreach($ob As $k=>$v) {
                $s .= $sDelim . $k . ' = ' . ':'.$k;
                $param[':'.$k] = $v;
                $sDelim = ', ';
            }

            $s .= ' Where prs_active = 0 And prs_type = 0 And prs_sec_id = 0 And prs_doc_id = 0 Limit 1';

//            Yii::info($s);
            $n = Yii::$app->db->createCommand($s, $param)->execute();
            if( $n == 0 ) {
                $oNew = new Person();
                $oNew->prs_type = $nType;
                $aTmp = $oNew->scenarios();
                $oNew->attributes = $ob;
                $oNew->prs_doc_id = $this->doc_id;
                $oNew->prs_active = Person::PERSON_STATE_ACTIVE;
                $oNew->prs_sec_id = $this->doc_sec_id;
                if( !$oNew->save() ) {
                    $bOk = false;
                    Yii::info('savePersons() nType = '.$nType.' attributes: ' . print_r($oNew->attributes, true));
                    Yii::info('savePersons() nType = '.$nType.' Error save oNew: ' . print_r($oNew->getErrors(), true));
                }
            }
        }

        return $bOk;
    }

    /**
     * @param $data
     */
    public function saveMedals($data) {
//        $aModels = $this->persons;
        $aNeedDel = [];
        Docmedal::updateAll(
            [
                'mdl_doc_id' => 0,
                'mdl_competition' => '',
                'mdl_title' => '',
            ],
            [
                'mdl_doc_id' => $this->doc_id,
            ]
        );

        foreach($data as $ob) {
            $ob['mdl_doc_id'] = $this->doc_id;

            Yii::info('medal = ' . print_r($ob, true));

            $s = 'Update ' . Docmedal::tableName() . ' Set ';
            $param = [];
            $sDelim = '';

            foreach($ob As $k=>$v) {
                $s .= $sDelim . $k . ' = ' . ':'.$k;
                $param[':'.$k] = $v;
                $sDelim = ', ';
            }

            $s .= ' Where mdl_doc_id = 0 Limit 1';

//            Yii::info($s);
            $n = Yii::$app->db->createCommand($s, $param)->execute();
            if( $n == 0 ) {
                $oNew = new Docmedal();
                $oNew->attributes = $ob;
                if( !$oNew->save() ) {
                    Yii::info('Error save Docmedal: ' . print_r($oNew->getErrors(), true));
                }
            }
        }

    }

    /**
     * Получение имени персоны
     *
     * @param bool $bShort
     * @return string
     */
    public function getLeadername($bShort = true) {
        return $bShort ?
            ($this->doc_lider_name . ' ' . $this->doc_lider_otch) :
            ($this->doc_lider_fam . ' ' . $this->doc_lider_name . ' ' . $this->doc_lider_otch);
    }

    /**
     * Текстовое представление ссостояния доклада
     *
     * @param bool $bShort
     * @return string
     */
    public function getFullState() {
        $s = '';
        $aStatuses = self::getAllStatuses();
        $aData = [
            'icon' => 'hourglass', // 'time'
            'text' => $this->getStatus(),
            'color' => '#00cc00',
        ];
        switch($this->doc_state) {

            case Doclad::DOC_STATUS_NEW:
                $aData = array_merge($aData, [
                    'color' => '#cc0000',
                ]);
                break;

            case Doclad::DOC_STATUS_NOT_APPROVE:
                $aData = array_merge($aData, [
                    'icon' => 'exclamation-sign',
                    'color' => '#cc0000',
                ]);
                break;

            case Doclad::DOC_STATUS_APPROVE:
                if( count($this->files) == 0 ){
                    $aData = array_merge($aData, [
                        'icon' => 'exclamation-sign',
                        'color' => '#cc0000',
                        'text' => $aData['text'] .  ', необходимо загрузить файл',
                    ]);
                }
                else {
                    $aData = array_merge($aData, [
                        'icon' => ($this->doc_state != self::DOC_FORMAT_NOFORMAT) ? 'ok' : 'time',
                        'color' => ($this->doc_state != self::DOC_FORMAT_NOFORMAT) ? $aData['color'] : '#cc0000',
                        'text' => $aData['text'] .  ', формат выступления: ' . $this->getFormat(),
                    ]);
                }
                break;

            case Doclad::DOC_STATUS_REVISION:
                $aData = array_merge($aData, [
                    'icon' => 'exclamation-sign',
                    'color' => '#cc0000',
                ]);
                break;

            default:
                $aData = array_merge($aData, [
                    'icon' => 'heart',
                    'color' => '#ffff33',
                ]);
                break;
        }

        return '<span class="glyphicon glyphicon-'.$aData['icon'].'" style="color: '.$aData['color'].';"></span> ' . Html::encode($aData['text']);
    }

}
