<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

use app\models\Doclad;
use app\models\Section;

/**
 * This is the model class for table "{{%conference}}".
 *
 * @property integer $cnf_id
 * @property string $cnf_title
 * @property string $cnf_class
 * @property string $cnf_controller
 * @property string $cnf_description
 * @property string $cnf_created
 * @property string $cnf_pagetitle
 * @property string $cnf_about
 * @property string $cnf_shorttitle
 * @property string $cnf_isconshicshool
 * @property integer $cnf_guestlimit
 */
class Conference extends \yii\db\ActiveRecord
{
    public static $_list = null; // список для форм и валидации
    public static $_alllist = null; // все записо

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['cnf_created'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%conference}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cnf_title', 'cnf_shorttitle', ], 'required'],
            [['cnf_description', 'cnf_about'], 'string'],
            [['cnf_created'], 'safe'],
            [['cnf_isconshicshool', 'cnf_guestlimit', ], 'integer'],
            [['cnf_isconshicshool'], 'in', 'range' => [0, 1], ],
            [['cnf_title', 'cnf_controller', 'cnf_pagetitle', 'cnf_shorttitle', ], 'string', 'max' => 255],
            [['cnf_class'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cnf_id' => 'Cnf ID',
            'cnf_title' => 'Название',
            'cnf_class' => 'Класс шаблона',
            'cnf_controller' => 'Контроллер',
            'cnf_description' => 'Текст',
            'cnf_created' => 'Создан',
            'cnf_pagetitle' => 'Заголовок страниц',
            'cnf_about' => 'Текст О конференции',
            'cnf_shorttitle' => 'Короткий заголовок',
            'cnf_isconshicshool' => 'Вводить ВУЗ для руководителя',
            'cnf_guestlimit' => 'Max кол-во гослей',
        ];
    }

    /**
     * @param $id id конференции
     * @param int $nType тип результата
     * @return string|Conference
     */
    public static function getById($id, $nType = 0) {
        if( $nType == 0 ) {
            $a = self::getList();
            $ret = isset($a[$id]) ? $a[$id] : null; // or throw new Excaption
        }
        else {
            $a = self::getAll();
            $ret = null;
            foreach($a As $ob) {
                if( $ob->cnf_id == $id ) {
                    $ret = $ob;
                    break;
                }
            }
        }
        return $ret;
    }

    /**
     * @return array for form select and validation
     */
    public static function getList() {
        if( self::$_list === null ) {
            self::$_list = ArrayHelper::map(self::getAll(), 'cnf_id', 'cnf_title');
        }
        return self::$_list;
    }

    /**
     * @return array with all records
     */
    public static function getAll() {
        if( self::$_alllist === null ) {
            self::$_alllist = self::find()->all();
        }
        return self::$_alllist;
    }

    /**
     * @param string|null $docType
     * @return \yii\db\ActiveQuery
     */
    public function getSections($docType = null) {
        $query = $this->hasMany(Section::className(), ['sec_cnf_id' => 'cnf_id']);

        // тут мы делаем выборку секций по типу доклада, если нужно не просто все связанные записи
        if( $docType !== null ) {
            $query->andOnCondition([
                'or',
                [Section::tableName() . '.sec_doclad_type' => $docType, ],
                [Section::tableName() . '.sec_doclad_type' => ''],
                ['is', Section::tableName() . '.sec_doclad_type', (new Expression('Null'))],
            ]);
        }

        return $query;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSectionwithguests() {
        $query = $this
            ->hasMany(
                Section::className(),
                ['sec_cnf_id' => 'cnf_id']
            )
            ->with('guests');
        return $query;
    }

    /**
     * @return int
     */
    public function getGuestcount() {
        return array_reduce(
            $this->sectionwithguests,
            function($carry, $el){
                return $carry + count($el->guests);
            },
            0
        );
    }
}
