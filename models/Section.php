<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use app\models\Conference;


/**
 * This is the model class for table "{{%section}}".
 *
 * @property integer $sec_id
 * @property string $sec_title
 * @property string $sec_cnf_id
 * @property string $sec_created
 */
class Section extends \yii\db\ActiveRecord
{

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
            [['sec_title', ], 'required'],
            [['sec_created'], 'safe'],
            [['sec_title'], 'string', 'max' => 255],
            [['sec_cnf_id'], 'string', 'max' => 64]
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
        ];
    }

    /**
     * Relation with conference
     * @return \yii\db\ActiveQuery
     */
    public function getConference() {
        return $this->hasOne(Conference::className(), ['cnf_id' => 'sec_cnf_id']);
    }
}
