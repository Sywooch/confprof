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
use app\models\Section;

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

    /**
     * СОздание групп пользователей и админа по умолчанию, если его еще нет в базе
     */
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

    /**
     * Импорт модераторов
     * Взят вордовский файл с секциями для инжененрных классов
     * Таблица скопирована в эксель и сохранена как csv
     * Ручками подправлено так, чтобы в строке были полностью данные по людям:
     * 1 столбец: номер
     * 2 столбец: ФИО и слова (ответственный эксперт)
     * 3 столбец: ВУЗ
     * 4 столбец: email
     * могут быть пустые строки
     */
    public function actionImportmoderate($filename, $idConference = 1)
    {
        if( ($hd = fopen($filename, "r")) !== false ) {
            $aFields = [
                '',
                'us_name',
                'us_description',
                'us_email'
            ];

            $aResultData = [];

            $sCurSection = '';
            $nSectionId = 0;
            $nRow = 0;
            while( ($data = fgetcsv($hd, 2000, ";")) !== false ) {
                $nRow++;
                $num = count($data);
                if( $num < 4 ) {
                    $this->printStr(
                        "Error fields: " . implode(', ', $data) . " num = ".$num." [{$nRow}]\n\n"
                    );
                    continue;
                }

                // *************************************************************************************************
                // конвертируем в UTF-8
                $data = array_map(function($s){ return iconv('CP1251', 'UTF-8', $s); }, $data);

                // *************************************************************************************************
                // тут пробуем найти секцию в строке, где только в первой ячейке название
                if( !empty($data[0]) && empty($data[1]) ) {
                    $aSec = Section::find()
                        ->where(
                            ['sec_title' => $data[0], 'sec_cnf_id' => $idConference, ]
                        )
                        ->all();
                    if( count($aSec) != 1 ) {
                        $this->printStr(
                            "Error find section: " . $data[0] . " n = ".count($aSec)." [{$nRow}]\n\n"
                        );
                        break;
//                        continue;
                    }
                    $sCurSection = $aSec[0]->sec_title;
                    $nSectionId = $aSec[0]->sec_id;
                    $this->printStr(
                        "New Section: " . $aSec[0]->sec_title . " [{$aSec[0]->sec_id}]\n\n"
//                        "Section: " . print_r($aSec[0]->attributes, true) . " [{$nRow}]\n\n"
                    );
                    continue;
                }

//                $this->printStr(
//                    "DATA: " . implode(', ', $data) . " num = ".$num." [{$nRow}]\n\n"
//                );

                // *************************************************************************************************
                // тут пропускаем строку с заголовками колонок
                if( (strpos($data[1], 'Фамилия') !== false) && (strpos($data[2], 'ВУЗ') !== false) ) {
//                    $this->printStr(
//                        "Col titles: " . implode(', ', $data) . " num = ".$num." [{$nRow}]\n\n"
//                    );
                    continue;
                }

                // *************************************************************************************************
                // тут получаем данные по полям строки
                $bEmpty = true; // это останется true в полностью пустой строке
                $aAttr = [];

                for ($i=0; $i < $num; $i++) {
                    if( !empty($data[$i]) ) {
                        $bEmpty = false;
//                        $this->printStr(
//                            "row [{$nRow}]: $i = " . $data[$i] . " -> {$aFields[$i]}\n\n"
//                        );
                        if( !empty($aFields[$i]) ) {
                            $aAttr[$aFields[$i]] = $data[$i];
                        }
                    }
                }

                // *************************************************************************************************
                // тут пропускаем полностью пустую строку
                if( $bEmpty ) {
//                    $this->printStr(
//                        "Empty row: " . implode(', ', $data) . " num = ".$num." [{$nRow}]\n\n"
//                    );
                    continue;
                }

                // *************************************************************************************************
                // тут пропускаем без почты
                if( !isset($aAttr['us_email']) ) {
                    $this->printStr(
                        "No email: " . implode(', ', $data) . " [{$nRow}]\n\n"
                    );
                    continue;
                }

                $oUser = User::find()
                    ->where(['us_email' => $aAttr['us_email']])
                    ->one();

                if( strpos($aAttr['us_name'], '(ответственный эксперт)') !== false ) {
                    $aAttr['us_name'] = str_replace('(ответственный эксперт)', '', $aAttr['us_name']);
                    $aAttr['us_description'] = "ответственный эксперт \t " . $aAttr['us_description'];
                }

//                $this->printStr(
//                    "Data row: " . print_r($aAttr, true) . " [{$nRow}]\n\n"
//                );

                if( $oUser === null ) {
                    //нового заводим
                    $oUser = new User();
                    $oUser->scenario = 'modregister';
                    $aAttr['password'] = Yii::$app->security->generateRandomString(6);
                    $oUser->us_group = User::USER_GROUP_MODERATOR;
                    $oUser->attributes = $aAttr;
                    $oUser->sectionids = [$nSectionId];
                    $oUser->us_active = User::STATUS_ACTIVE;

                    if( !$oUser->save() ) {
                        $this->printStr(
                            "Error save new user: " . print_r($oUser->getErrors(), true) . print_r($oUser->attributes, true) . " [{$nRow}]\n\n"
                        );
                    }
                    else {
                        $aResultData[] = [
                            'us_name' => $aAttr['us_name'],
                            'password' => $aAttr['password'],
                            'us_description' => $aAttr['us_description'],
                            'section' => $sCurSection,
                            'us_email' => $aAttr['us_email'],
                        ];
                    }
                }
                else {
                    // пробуем добавить новую секцию
                    if( !in_array($nSectionId, $oUser->sectionids) ) {
                        $oUser->sectionids = array_merge($oUser->sectionids, [$nSectionId]);
                        if( !$oUser->save() ) {
                            $this->printStr(
                                "Error save user sections: " . print_r($oUser->getErrors(), true) . print_r($oUser->sectionids, true) . " [{$nRow}]\n\n"
                            );
                        }
                        else {
//                            $this->printStr(
//                                "Save user sections: " . print_r($oUser->attributes, true) . print_r($oUser->sectionids, true) . " [{$nRow}]\n\n"
//                            );
                        }
                    }
                    else {
//                        $this->printStr(
//                            "User has section [{$nSectionId}]: " . print_r($oUser->attributes, true) . print_r($oUser->sectionids, true) . " [{$nRow}]\n\n"
//                        );
                    }
                }

//                break;
            }
            fclose($hd);
            if( count($aResultData) > 0 ) {
                $sFile = dirname($filename) . DIRECTORY_SEPARATOR . 'users-' . time() . '.csv';
                $this->printResult($aResultData, $sFile);
                $this->printStr(
                    "Users save: " . count($aResultData) . ", file {$sFile}\n\n"
                );
            }
        }
        else {
            echo "\n\nCan't open file {$filename}\n\n";
        }
    }

    /**
     * Вывод для консоли
     * @param string $s
     *
     */
    public function printStr($s) {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $s = iconv('UTF-8', 'CP866//IGNORE', $s); //  . $s;
        }
        echo $s;
    }

    /**
     * Вывод паролей
     * @param array $a
     * @param string $sfile
     *
     */
    public function printResult($a, $sfile) {
        $s = '';
        $sSec = '';
        foreach($a As $v) {
            $v = array_map(function($s){ return iconv('UTF-8', 'CP1251', $s); }, $v);
            if( $sSec !== $v['section'] ) {
                $sSec = $v['section'];
                $s .= $sSec . "\n";
//                continue;
            }
            $s .= $v['us_name'] . ';' . $v['password'] . ';' . $v['us_email'] . "\n";
        }
        file_put_contents($sfile, $s);
    }

}
