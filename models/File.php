<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%file}}".
 *
 * @property integer $file_id
 * @property string $file_time
 * @property string $file_orig_name
 * @property integer $file_doc_id
 * @property integer $file_us_id
 * @property integer $file_size
 * @property string $file_type
 * @property string $file_name
 */
class File extends \yii\db\ActiveRecord
{

    /**
     * @return array
     */
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['file_time'],
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
        return '{{%file}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file_time'], 'safe'],
            [['file_orig_name', 'file_size', 'file_name'], 'required'],
            [['file_doc_id', 'file_us_id', 'file_size'], 'integer'],
            [['file_orig_name', 'file_type', 'file_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'file_id' => 'ID',
            'file_time' => 'Время',
            'file_orig_name' => 'Имя файла',
            'file_doc_id' => 'Доклад',
            'file_us_id' => 'Пользователь',
            'file_size' => 'Размер',
            'file_type' => 'Тип',
            'file_name' => 'Внутр. имя',
        ];
    }

}
