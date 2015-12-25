<?php

namespace app\models;

use Yii;
use app\models\Person;
use app\models\Member;
use app\models\Docmedal;

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
 * @property string $doc_lider_level
 * @property string $doc_lider_position
 * @property string $doc_lider_lesson
 */
class Doclad extends \yii\db\ActiveRecord
{
    const DOC_TYPE_PERSONAL = 'person';
    const DOC_TYPE_ORG = 'org';

    public $aSectionList = [];
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
            [['doc_sec_id', 'ekis_id', 'doc_us_id', ], 'integer'],
            [['doc_sec_id', ], 'in', 'range' => array_keys($this->aSectionList)],
            [['doc_type', 'doc_sec_id', 'doc_subject', 'doc_description', 'doc_lider_fam', 'doc_lider_name', 'doc_lider_otch', 'doc_lider_email', 'doc_lider_phone', 'ekis_id', 'doc_lider_group', 'doc_lider_level', 'doc_lider_position', 'doc_lider_lesson'], 'required'],
            [['doc_description'], 'string'],
            [['doc_created'], 'safe'],
            [['doc_type'], 'string', 'max' => 16],
            [['doc_type'], 'in', 'range' => array_keys(self::getAllTypes())],
            [['doc_subject', 'doc_lider_fam', 'doc_lider_name', 'doc_lider_otch', 'doc_lider_email', 'doc_lider_phone', 'doc_lider_org', 'doc_lider_group', 'doc_lider_level', 'doc_lider_position', 'doc_lider_lesson'], 'string', 'max' => 255]
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
                    'doc_lider_level',
                ]
            );
        }

        $aRet['confirmregister'] = [ // подтвкрждение регистрации
            'us_active',
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
            'doc_lider_group' => 'Класс',
            'doc_lider_level' => 'Курс',
            'doc_lider_position' => 'Должность',
            'doc_lider_lesson' => 'Предмет',
        ];
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
        return $this->hasMany(Member::className(), ['prs_doc_id' => 'doc_id'])->where(['prs_type' => Person::PERSON_TYPE_PARTNER]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedals() {
        return $this->hasMany(Docmedal::className(), ['mdl_doc_id' => 'doc_id']);
    }

    /**
     * @param $data
     */
    public function saveConsultants($data) {
        $aModels = $this->persons;
        $aNeedDel = [];
        Person::updateAll(
            [
                'prs_active' => 0,
                'prs_type' => 0,
                'prs_sec_id' => 0,
                'prs_doc_id' => 0,
            ],
            [
                'prs_doc_id' => $this->doc_id,
            ]
        );

        foreach($data as $ob) {
            $ob['prs_type'] = Person::PERSON_TYPE_CONSULTANT;
            $ob['prs_doc_id'] = $this->doc_id;
            $ob['prs_active'] = Person::PERSON_STATE_ACTIVE;

            Yii::info('ob = ' . print_r($ob, true));

            $s = 'Update ' . Person::tableName() . ' Set ';
            $param = [];
            $sDelim = '';

            foreach($ob As $k=>$v) {
                $s .= $sDelim . $k . ' = ' . ':'.$k;
                $param[':'.$k] = $v;
                $sDelim = ', ';
            }

            $s .= ' Where prs_active = 0 And prs_type = 0 And prs_sec_id = 0 And prs_doc_id = 0 Limit 1';

            Yii::info($s);
            $n = Yii::$app->db->createCommand($s, $param)->execute();
            if( $n == 0 ) {
                $oNew = new Person();
                $oNew->attributes = $ob;
                if( !$oNew->save() ) {
                    Yii::info('Error save oNew: ' . print_r($oNew->getErrors(), true));
                }
            }
        }

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
}
