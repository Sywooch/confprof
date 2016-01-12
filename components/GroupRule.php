<?php

namespace app\components;

use yii;
use yii\rbac\Rule;
use app\models\User;

class GroupRule extends Rule {

    public $name = 'userGroup';

    public function execute($user, $item, $params) {
        if( !Yii::$app->user->isGuest ) {
            $group = Yii::$app->user->identity->us_group;
            if( $item->name === User::USER_GROUP_PERSONAL ) {
                return $group == User::USER_GROUP_PERSONAL;
            } elseif( $item->name === User::USER_GROUP_ORGANIZATION ) {
                return $group == User::USER_GROUP_ORGANIZATION;
            } elseif( $item->name === User::USER_GROUP_MODERATOR ) {
                return $group == User::USER_GROUP_MODERATOR;
            }
        }
        return false;
    }

}