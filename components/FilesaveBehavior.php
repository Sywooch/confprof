<?php
/**
 * Created by PhpStorm.
 * User: KozminVA
 * Date: 30.12.2015
 * Time: 11:56
 */

namespace app\components;

use yii;
use yii\base\Behavior;
use yii\web\UploadedFile;
use yii\base\InvalidConfigException;
use yii\base\InvalidValueException;

class FilesaveBehavior extends Behavior {
    /**
     * @var string model for save file path for uploaded file
     */
    public $filesaveFileModel = null;

    /**
     * @var array with key is attribute name in $filesaveFileModel model and value is filed name in UploadedFile model or value to assign to attribute
     * if value == 'fullpath' the saved file path relative from @webroot will be set
     * if value == 'parentid' the parent record id will be set
     */
    public $filesaveConvertFields = null;

    /**
    /**
     * @var string directory name for save uploaded file
     */
    public $filesaveBaseDirName = null;

    /**
     * @var string file name prefix for uploaded file
     */
    public $filesaveFilenamePrefix = '';

    /**
     * @var int max count for upload files
     */
    public $filesaveMaxcountFileCount = null;

    /**
     * @var null|Closure function for create full file path
     * function($this->filesaveBaseDirName, $sName, $parentModelId)
     */
    public $filesaveCreateFullPath = null;

    /**
     *
     */
    public function init() {
        parent::init();

        if( ($this->filesaveFileModel !== null) && ($this->filesaveConvertFields === null) ) {
            throw new InvalidConfigException('"filesaveConvertFields" property must be specified for model ' . $this->filesaveFileModel);
        }

        if( ($this->filesaveBaseDirName === null) && ($this->filesaveCreateFullPath === null) ) {
            throw new InvalidConfigException('"filesaveBaseDirName" or "filesaveCreateFullPath" property must be specified');
        }
    }

    /**
     * @param integer $parentModelId
     */
    public function uploadFile($parentModelId = null) {
        $aReturn = [];

        $files = UploadedFile::getInstances($this->owner, 'file');
//        Yii::info("uploadFiles() files = " . print_r($files, true));

        // if no file was uploaded abort the upload
        if( empty($files) ) {
            return $aReturn;
        }

        if( !$this->isUploadDirExists() ) {
            throw new InvalidValueException('Directory for upload file does not exists');
//            return $aReturn;
        }

        $nCou = 0; // $this->filesaveMaxcountFileCount;
        $sRoorDir = str_replace('/', DIRECTORY_SEPARATOR, Yii::getAlias('@webroot'));

        foreach($files As $ob) {
            /** @var UploadedFile $ob */
            $nCou++;
            $aData = [
                'name' => $ob->name,
            ];

            if( ($this->filesaveMaxcountFileCount !== null) && ($nCou > $this->filesaveMaxcountFileCount) ) {
                $aData['error'] = ['File exceed max number of files ('.$nCou.' from '.$this->filesaveMaxcountFileCount.')'];
                continue;
            }

            $sName = $this->filesaveFilenamePrefix . $ob->name;
            $sFullName = $this->filesaveCreateFullPath === null ?
                $this->getFullpath($sName) :
                $this->filesaveCreateFullPath($this->filesaveBaseDirName, $sName, $parentModelId);

            $upCount = 1;
            while( file_exists($sFullName) ) {
                $p = strrpos($sName, '.');
                if( $p === false ) {
                    $p = strlen($sName);
                }

                $sExt = substr($sName, $p);
//                echo $sName . ' = ' . $sExt . ' + ';
                $sName = substr($sName, 0, $p);
//                echo $sName . '<br />';
                if( preg_match('|(-[\\d]+)$|', $sName) ) {
                    $sName = preg_replace('|(-[\\d]+)$|', '-' . $upCount, $sName);
                }
                else {
                    $sName .= '-' . $upCount;
                }
//                echo 'New: ' . $sName . '' . $sExt . '<br />';
                $sName .= $sExt;

//                if( $upCount > 2 ) {
//                    die();
//                }

                $sFullName = $this->filesaveCreateFullPath === null ?
                    $this->getFullpath($sName) :
                    $this->filesaveCreateFullPath($this->filesaveBaseDirName, $sName, $parentModelId);
                $upCount++;
//                Yii::info('New file name: ' . $sFullName . ' ('.$upCount.')');
            }

            $ob->saveAs($sFullName);

            $aData['fullname'] = substr($sFullName, strlen($sRoorDir));

            if( $this->filesaveFileModel !== null ) {
                $sModel = $this->filesaveFileModel;
                // TODO: тут лукавство - а вдруг будем сохранять не публичной части?
                $aExtra = [
                    'fullpath' => substr($sFullName, strlen($sRoorDir)),
                    'parentid' => $parentModelId,
                ];
                $oFileModel = new $sModel();
                foreach($this->filesaveConvertFields As $k=>$v) {
                    if( is_string($v) && property_exists($ob, $v) ) {
                        $oFileModel->{$k} = $ob->{$v};
//                        Yii::info('Set data File->' . $k . ' = ' . $ob->{$v});
                    }
                    else {
                        if( isset($aExtra[$v]) ) {
                            $oFileModel->{$k} = $aExtra[$v];
//                            Yii::info('Set extra File->' . $k . ' = ' . $aExtra[$v]);
                        }
                        else {
                            $oFileModel->{$k} = $v;
//                            Yii::info('Set val File->' . $k . ' = ' . $v);
                        }
                    }
                }

                if( !$oFileModel->save() ) {
                    $aData['error'] = $oFileModel->getErrors();
                    Yii::info('Error save uploaded file ' . $sName . ' ' . print_r($aData['error'], true));
                }
            }
            $aReturn[] = $aData;
        }
        return $aReturn;
    }

    /**
     * Test if upload dir exists and try to create one if not
     *
     */
    public function isUploadDirExists() {

        if( $this->filesaveBaseDirName === null ) {
            return true;
        }

        $sDir = Yii::getAlias($this->filesaveBaseDirName);
//        Yii::info("Upload dir: {$sDir}");
        if( !is_dir($sDir) ) {
//            Yii::info("Upload dir: {$sDir} not exists");
            $a = explode('/', $this->filesaveBaseDirName);
            $s = '';
            while( count($a) > 0 ) {
                $s .= (($s === '') ? '' : '/') . array_shift($a);
                $sd = Yii::getAlias($s);
//                Yii::info("Upload dir: try {$s} = {$sd}");
                if( !is_dir($sd) && !mkdir($sd) ) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     *
     * Make full path to file
     * @param $sFilename string file name
     * @return string full path to file
     *
     */
    public function getFullpath($sFilename) {
        $sSubdir = substr(md5(basename($sFilename)), 0, 2);
        $sDir = str_replace('/', DIRECTORY_SEPARATOR, Yii::getAlias($this->filesaveBaseDirName))
            . DIRECTORY_SEPARATOR
            . $sSubdir;
        if( !is_dir($sDir) && !@mkdir($sDir) ) {
            Yii::info('Does not exists directory ' . $sDir . ' ('.$sFilename.')');
            return null;
        }
        return $sDir . DIRECTORY_SEPARATOR . $sFilename;
    }

}