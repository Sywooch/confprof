<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 *
 */
class NewpasswordForm extends Model
{
    public $password;
    public $password_repeat;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['password', 'password_repeat', ], 'required'],
            [['password', 'password_repeat', ], 'string', 'min' => 3, ],
            [['password_repeat', ], 'compare', 'compareAttribute' => 'password', ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password' => 'Пароль',
            'password_repeat' => 'Повтор пароля',
        ];
    }

}
