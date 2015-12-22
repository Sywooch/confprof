<?php
/**
 * Created by PhpStorm.
 * User: Vik
 * Date: 08.11.2015
 * Time: 23:44
 */

namespace app\components;


class Notificator {

    public $obList = [];
    public $template = null;
    public $oData = null;

    public function __construct($obList, $oData, $template) {
        $this->obList = $obList;
        $this->oData = $oData;
        $this->template = $template;
    }

    public function notifyMail($subject) {
        $aMails = [];
        foreach($this->obList As $ob) {
            $aMails[] = \Yii::$app->mailer
                ->compose($this->template, ['model' => $ob, 'data'=>$this->oData])
                ->setFrom(\Yii::$app->params['fromEmail'])
                ->setTo($ob->us_email)
                ->setSubject($subject);
        }
        if( count($aMails) > 0 ) {
            \Yii::$app->mailer->sendMultiple($aMails);
        }
    }

}