<?php

namespace app\components;

use yii;
use yii\rbac\Rule;
use app\models\User;

class GroupRule extends Rule {

    public $name = 'userGroup';

    public function execute($user, $item, $params) {
        Yii::info(self::className() . ' : execute 1: ' . print_r($item, true) . ' ' . print_r($params, true));
        if( !Yii::$app->user->isGuest ) {
            Yii::info(self::className() . ' : execute 2 ' . $item->name);
            $group = Yii::$app->user->identity->us_group;
            $b = false;
            if( $item->name === User::USER_GROUP_PERSONAL ) {
                $b = $group == User::USER_GROUP_PERSONAL;
            } elseif( $item->name === User::USER_GROUP_ORGANIZATION ) {
                $b = $group == User::USER_GROUP_ORGANIZATION;
            } elseif( $item->name === User::USER_GROUP_MODERATOR ) {
                $b = ($group == User::USER_GROUP_MODERATOR) || ($group == User::USER_GROUP_ADMIN);
            } elseif( $item->name === User::USER_GROUP_ADMIN ) {
                $b = $group == User::USER_GROUP_ADMIN;
            }
            Yii::info(self::className() . ' : item->name = ' . $item->name . ' group = ' . $group . ' => ' . ($b ? 'true' : 'false'));
            return $b;
        }
        return false;
    }

}