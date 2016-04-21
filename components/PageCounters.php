<?php
/**
 * Created by PhpStorm.
 * User: KozminVA
 * Date: 21.04.2016
 * Time: 12:46
 */

namespace app\components;


class PageCounters {

    public static function insertCounters() {
        return <<<EOT

<!--LiveInternet counter--><script type="text/javascript"><!--
    new Image().src = "//counter.yadro.ru/hit?r"+
    escape(document.referrer)+((typeof(screen)=="undefined")?"":
    ";s"+screen.width+"​*"+screen.height+"*​"+(screen.colorDepth?
        screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
    ";"+Math.random();//--></script><!--/LiveInternet-->

EOT;

    }

}