<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\_User;
use yii;
use yii\console\Controller;

use app\components\GroupRule;
use app\models\User;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class RbacController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'Usage: rbac/create')
    {
        echo "\n" . $message . "\n";
    }

    public function actionCreate()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        $sdir = dirname($auth->assignmentFile);

        if( !is_dir($sdir) ) {
            if( !mkdir($sdir) ) {
                echo 'Can not create directory: ' . $sdir . "\n";
                return;
            }
            chmod($sdir, 0777);
        }

        $rule = new GroupRule();
        $auth->add($rule);

        $moderator = $auth->createRole(User::USER_GROUP_MODERATOR);
        $moderator->ruleName = $rule->name;
        $auth->add($moderator);

        $personal = $auth->createRole(User::USER_GROUP_PERSONAL);
        $personal->ruleName = $rule->name;
        $auth->add($personal);

        $organiszate = $auth->createRole(User::USER_GROUP_ORGANIZATION);
        $organiszate->ruleName = $rule->name;
        $auth->add($organiszate);

        $participant = $auth->createRole('participant');
        $auth->add($participant);
        $auth->addChild($participant, $organiszate);
        $auth->addChild($participant, $personal);

        $admin = $auth->createRole(User::USER_GROUP_ADMIN);
        $auth->add($admin);
        $auth->addChild($admin, $moderator);

        $Admin = User::find()->where(['us_group' => [User::USER_GROUP_MODERATOR, User::USER_GROUP_ADMIN, ]])->one();
        if( $Admin === null ) {
            $Admin = new User();
            $Admin->scenario = 'modregister';
            $Admin->us_active = 1;
            $Admin->us_email = '456@mail.ru';
            $Admin->us_group = User::USER_GROUP_ADMIN;
            $Admin->password = '111111';
            if( !$Admin->save() ) {
                $sErr = print_r($Admin->getErrors(), true);
                if( DIRECTORY_SEPARATOR == '\\' ) {
                    $sErr = iconv('UTF-8', 'CP866', $sErr);
                }
                echo 'Error save admin: ' . $sErr . "\n";
            }
        }
    }

}
