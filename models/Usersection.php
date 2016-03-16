<?php

namespace app\models;

use Yii;
use app\models\Section;

/**
 * This is the model class for table "{{%usersection}}".
 *
 * @property integer $usec_id
 * @property integer $usec_user_id
 * @property integer $usec_section_id
 * @property integer $usec_section_primary
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
            [['usec_user_id', 'usec_section_id', 'usec_section_primary', ], 'integer']
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
            'usec_section_primary' => 'Primary moderator',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSection() {
        return $this->hasOne(Section::className(), ['sec_id' => 'usec_section_id']);
    }
}
