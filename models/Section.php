<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

use app\models\Conference;
use app\models\Doclad;
use app\models\Person;

/**
 * This is the model class for table "{{%section}}".
 *
 * @property integer $sec_id
 * @property string $sec_title
 * @property string $sec_cnf_id
 * @property string $sec_created
 * @property string $sec_doclad_type
 */
class Section extends \yii\db\ActiveRecord
{
    public static $_cache = [];

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['sec_created'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%section}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sec_title', ], 'required', ],
            [['sec_created'], 'safe', ],
            [['sec_title'], 'string', 'max' => 255, ],
            [['sec_cnf_id'], 'string', 'max' => 64, ],
            [['sec_doclad_type'], 'string', 'max' => 16, ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sec_id' => 'Sec ID',
            'sec_title' => 'Название',
            'sec_cnf_id' => 'Конференция',
            'sec_created' => 'Создан',
            'sec_doclad_type' => 'Тип доклада',
        ];
    }

    /**
     * Relation with conference
     * @return \yii\db\ActiveQuery
     */
    public function getConference() {
        return $this->hasOne(Conference::className(), ['cnf_id' => 'sec_cnf_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDoclads() {
        return $this->hasMany(Doclad::className(), ['doc_sec_id' => 'sec_id']);
    }

    /**
     * @param array $aWhere
     * @return mixed
     */
    public static function getSectionList($aWhere = []) {
        $sKey = md5(print_r($aWhere, true));
        if( !isset(self::$_cache[$sKey]) ) {
            $query = self::find();
            if( !empty($aWhere) ) {
                $query->where($aWhere);
            }
            self::$_cache[$sKey] = ArrayHelper::map(
                $query->all(),
                'sec_id',
                'sec_title'
            );
        }
        return self::$_cache[$sKey];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGuests() {
        return $this->hasMany(
            Person::className(),
            [
                'prs_sec_id' => 'sec_id',
            ]
        )
            ->where([
                'prs_active' => Person::PERSON_STATE_ACTIVE,
                'prs_type' => Person::PERSON_TYPE_GUEST,
            ]);
    }


}
