<?php
/**
 * Created by PhpStorm.
 * User: Vik
 * Date: 08.11.2015
 * Time: 23:44
 */

namespace app\components;

use yii;

class Notificator {

    public $obList = [];
    public $template = null;
    public $oData = null;
    public $sEmailField = 'us_email';

    public function __construct($obList, $oData, $template) {
        $this->obList = $obList;
        $this->oData = $oData;
        $this->template = $template;
    }

    /**
     * @param string $subject
     */
    public function notifyMail($subject) {
        $aMails = [];
        $fldEmail = $this->sEmailField;
        foreach($this->obList As $ob) {
            $aMails[] = \Yii::$app->mailer
                ->compose($this->template, ['model' => $ob, 'data'=>$this->oData])
                ->setFrom(\Yii::$app->params['fromEmail'])
                ->setTo($ob->{$fldEmail})
                ->setSubject($subject);
        }
        if( count($aMails) > 0 ) {
            foreach($aMails As $k=>$v) {
                SwiftHeaders::setAntiSpamHeaders($aMails[$k], ['email' => Yii::$app->params['fromEmail']]);
            }
            \Yii::$app->mailer->sendMultiple($aMails);
        }
    }

    /**
     * @param array $aEmail user's email array
     * @param string $sSubject mail subject
     * @param string $sHtmltext mail body
     * @return string
     */
    public function massMail($aEmail, $sSubject, $sHtmltext) {

        $data = [
            'email' => $aEmail,
            'subject' => $sSubject,
            'body' => $sHtmltext,
        ];

        $context = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type:  application/json' . PHP_EOL . 'Host: ' . 'm.educom.ru' . PHP_EOL,
                'content' => json_encode($data),
                'timeout' => 15, // sec
            ),
        ));

        $res =  file_get_contents(
//            $file = "http://m.educom.ru/component/mail/gate",
            $file = "http://10.128.1.195/component/mail/gate",
            $use_include_path = false,
            $context);

        $data['res'] = $res;
        return $data;
    }

    /**
     * @param integer $id id mail sending on m.educom.ru
     * @return array
     */
    public function getMailStat($id) {
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => 'Content-Type:  application/json' . PHP_EOL . 'Host: ' . 'm.educom.ru' . PHP_EOL,
                'timeout' => 5, // sec
            ),
        ));

        $res =  file_get_contents(
            $file = "http://10.128.1.195/component/mail/gate/" . $id,
            $use_include_path = false,
            $context);

        if( $res !== false ) {
            $res = json_decode($res, true);
        }

        return $res;
    }
}