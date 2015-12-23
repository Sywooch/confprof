<?php

namespace app\models;

use Yii;

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
 * @property string $prs_level
 * @property string $prs_position
 * @property string $prs_lesson
 */
class Person extends \yii\db\ActiveRecord
{
    const PERSON_TYPE_GUEST = 1;
    const PERSON_TYPE_CONSULTANT = 2;
    const PERSON_TYPE_PARTNER = 3;

    const PERSON_STATE_ACTIVE = 1;
    const PERSON_STATE_NONACTIVE = 0;
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
        return [
//            [['prs_type', ], 'filter', 'filter'=>[$this, 'setPersonType'], ],
            [['prs_active', 'prs_type', 'prs_sec_id', 'prs_doc_id', 'ekis_id'], 'integer'],
            [['prs_type', 'prs_fam', 'prs_name', 'prs_otch', 'prs_email', 'prs_position', 'prs_lesson'], 'required'],
            [['prs_fam', 'prs_name', 'prs_otch', 'prs_org'], 'string', 'max' => 255],
            [['prs_email'], 'string', 'max' => 128],
            [['prs_phone'], 'string', 'max' => 24],
            [['prs_group', 'prs_level', 'prs_position', 'prs_lesson'], 'string', 'max' => 64]
        ];
    }

    /**
     * фильтр для установки типа по сценарию
     * @param $val string
     */
//    public function setPersonType($val) {
//        $a = [
//            'createconsultant' => self::PERSON_TYPE_CONSULTANT,
//            'createguest' => self::PERSON_TYPE_GUEST,
//            'createpartner' => self::PERSON_TYPE_PARTNER,
//        ];
//
//        if( isset($a[$this->scenario]) ) {
//            return $a[$this->scenario];
//        }
//        else {
//            if( $this->prs_type ==  )
//        }
//        return $this->prs_type;
//    }

    /**
     * Сценарии
     * @return array
     */
    public function scenarios() {
        $aRet = parent::scenarios();
        if( $this->prs_type == self::PERSON_TYPE_CONSULTANT ) {
            $this->scenario = 'createconsultant';
        }
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
        ];

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
            'prs_group' => 'Класс',
            'prs_level' => 'Курс',
            'prs_position' => 'Должность',
            'prs_lesson' => 'Предмет',
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
            self::PERSON_TYPE_PARTNER => 'Соучастник',
        ];

    }

    /**
     * Название типа персоны
     * @return string|null
     */
    public function getTypename() {
        $a = self::getAllTypes();
        return isset($a[$this->prs_type]) ? $a[$this->prs_type] : null;
    }
}
