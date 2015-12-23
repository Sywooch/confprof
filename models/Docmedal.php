<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%docmedal}}".
 *
 * @property integer $mdl_id
 * @property string $mdl_competition
 * @property string $mdl_title
 * @property integer $mdl_doc_id
 */
class Docmedal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%docmedal}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mdl_competition', 'mdl_title'], 'required'],
            [['mdl_doc_id'], 'integer'],
            [['mdl_competition', 'mdl_title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mdl_id' => 'Mdl ID',
            'mdl_competition' => 'Конференция / конкурс',
            'mdl_title' => 'Результат',
            'mdl_doc_id' => 'Доклад',
        ];
    }
}
