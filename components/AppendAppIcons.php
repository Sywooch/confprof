<?php
/**
 * Created by PhpStorm.
 * User: KozminVA
 * Date: 12.05.2015
 * Time: 15:49
 */

namespace app\components;

use yii;

class AppendAppIcons {
    /**
     * Устанавливаем иконки приложения
     *
     */
    public static function getIconList($sHost = null) {
        if( $sHost === null ) {
            $sHost = $_SERVER['HTTP_HOST'];
        }
        return '<link href="' . $sHost . '/favicon.ico' . '" rel="shortcut icon" type="image/vnd.microsoft.icon" />' . "\n"
            . '<link href="http://' . $sHost . '/apple-touch-icon.png" rel="apple-touch-icon" />' . "\n"
            . '<link href="http://' . $sHost . '/apple-touch-icon-76.png" rel="apple-touch-icon" sizes="76x76" />' . "\n"
            . '<link href="http://' . $sHost . '/apple-touch-icon-120.png" rel="apple-touch-icon" sizes="120x120" />' . "\n"
            . '<link href="http://' . $sHost . '/apple-touch-icon-152.png" rel="apple-touch-icon" sizes="152x152" />' . "\n"
            . '<link href="http://' . $sHost . '/apple-touch-icon-180.png" rel="apple-touch-icon" sizes="180x180" />' . "\n"
            . '<link href="http://' . $sHost . '/apple-touch-icon-precomposed.png" rel="apple-touch-icon-precomposed" />' . "\n"
            . '<link href="http://' . $sHost . '/apple-touch-icon-76-precomposed.png" rel="apple-touch-icon-precomposed" sizes="76x76" />' . "\n"
            . '<link href="http://' . $sHost . '/apple-touch-icon-120-precomposed.png" rel="apple-touch-icon-precomposed" sizes="120x120" />' . "\n"
            . '<link href="http://' . $sHost . '/apple-touch-icon-152-precomposed.png" rel="apple-touch-icon-precomposed" sizes="152x152" />' . "\n"
            . '<link href="http://' . $sHost . '/apple-touch-icon-180-precomposed.png" rel="apple-touch-icon-precomposed" sizes="180x180" />' . "\n"
            . '<link href="http://' . $sHost . '/icon-hires.png" rel="icon" sizes="192x192" />' . "\n"
            . '<link href="http://' . $sHost . '/icon-normal.png" rel="icon" sizes="128x128" />' . "\n"
            . '<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">' . "\n"
            . '<link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">' . "\n"
            . '<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">' . "\n";

    }
}