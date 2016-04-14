<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class EduCounterAsset extends AssetBundle
{
//    public $basePath = '@webroot';
    public $baseUrl = 'https://stats.mos.ru/';
    public $css = [];
    public $js = [
        'counter.js'
    ];
    public $jsOptions = [
        'id' => "statsmosru",
        'onLoad' => "statsMosRuCounter()",
        'defer' => "defer",
        'async' => "true",
    ];
}
