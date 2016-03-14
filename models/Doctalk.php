<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

use app\models\User;

/**
 * This is the model class for table "{{%doctalk}}".
 *
 * @property integer $dtlk_id
 * @property integer $dtlk_us_id
 * @property integer $dtlk_doc_id
 * @property string $dtlk_text
 * @property string $dtlk_created
 */
class Doctalk extends \yii\db\ActiveRecord
{

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['dtlk_created'],
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
        return '{{%doctalk}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dtlk_us_id', 'dtlk_doc_id'], 'integer'],
            [['dtlk_text', 'dtlk_doc_id', ], 'required'],
            [['dtlk_text'], 'string'],
            [['dtlk_created'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dtlk_id' => 'Dtlk ID',
            'dtlk_us_id' => 'Пользователь',
            'dtlk_doc_id' => 'Доклад',
            'dtlk_text' => 'Комментарий',
            'dtlk_created' => 'Создан',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAutor() {
        return $this->hasOne(User::className(), ['us_id' => 'dtlk_us_id']);
    }
}
