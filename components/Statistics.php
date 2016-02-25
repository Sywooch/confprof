<?php
/**
 * Created by PhpStorm.
 * User: KozminVA
 * Date: 24.02.2016
 * Time: 18:21
 */

namespace app\components;

use yii;
use yii\helpers\ArrayHelper;
use PDO;

use app\models\Section;
use app\models\Doclad;


class Statistics {

    /**
     * @param int|array $id id секций для получения по ним статистики
     */
    public static function getConferenceStat($id = 0) {

        // посчитаем гостей, руководителей и участников
        $sWhere = ($id === 0) ?
            'prs_sec_id > 0' :
            (is_array($id) ?
                ('prs_sec_id in ('.implode(',', $id).')') :
                ('prs_sec_id = '.$id)
            );
        $sSql = <<<EOT
Select SUM(IF(p.prs_type = 1, 1, 0 )) As cou_guest,
       SUM(IF(p.prs_type = 3 Or p.prs_type = 4, 1, 0 )) As cou_member,
       SUM(IF(p.prs_type = 2, 1, 0 )) As cou_consult,
       prs_sec_id
From confprof_person p
Where {$sWhere}
Group By prs_sec_id
Order By prs_sec_id
EOT;
        $aSectPerson = ArrayHelper::map(
            Yii::$app->db->createCommand($sSql)->queryAll(PDO::FETCH_ASSOC),
            'prs_sec_id',
            function($el) {
                return $el;
            }
        );

        // пробежимся по секциям и докладам
        $q = Section::find()->with(['conference', 'doclads', ]);
        if( $id !== 0 ) {
            $q->where(['prs_sec_id' => $id]);
        }
        $aSect = $q->all();
        $aRet = [];
        foreach( $aSect As $oSect ) {
            /** @var Section $oSect */
            $adata = [
                'sect_id' => $oSect->sec_id,
                'sect' => $oSect->sec_title,
                'conf' => $oSect->conference->cnf_title,
                'doclads' => count($oSect->doclads),
            ];

            $adata = array_merge(
                $adata,
                isset($aSectPerson[$oSect->sec_id]) ? $aSectPerson[$oSect->sec_id] : ['cou_guest' => 0, 'cou_member' => 0, 'cou_consult' => 0, ]
            );

            // посчитаем уникальных лидеров проектов
            $aLeader = [];
            foreach( $oSect->doclads As $oDocl ) {
                /** @var Doclad $oDocl */
                if( !isset($aLeader[$oDocl->doc_lider_email]) ) {
                    $aLeader[$oDocl->doc_lider_email] = 0;
                }
                $aLeader[$oDocl->doc_lider_email]++; // это на всякий случай
            }
            $adata['leaders'] = count($aLeader);

            $aRet[] = $adata;
        }

        return $aRet;
    }

}