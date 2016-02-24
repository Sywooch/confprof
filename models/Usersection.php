<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%usersection}}".
 *
 * @property integer $usec_id
 * @property string $usec_user_id
 * @property string $usec_section_id
 */
class Usersection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%usersection}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['usec_user_id', 'usec_section_id'], 'required'],
            [['usec_user_id', 'usec_section_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'usec_id' => 'Usec ID',
            'usec_user_id' => 'Usec User ID',
            'usec_section_id' => 'Usec Section ID',
        ];
    }
}
