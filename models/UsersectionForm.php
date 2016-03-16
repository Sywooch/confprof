<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Section;

class UsersectionForm extends Model {

    public $sectid;
    public $isprime;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['sectid', ], 'required'], // 'userid',
            [['sectid', 'isprime', ], 'integer'],
            [['sectid', ], 'in', 'range'=>array_keys(Section::getSectionList()), ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sectid' => 'Секция',
            'isprime' => 'Ответственный',
        ];
    }
}