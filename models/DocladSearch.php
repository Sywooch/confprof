<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Doclad;
use app\components\SearchappendBehavior;

/**
 * DocladSearch represents the model behind the search form about `app\models\Doclad`.
 */
class DocladSearch extends Doclad
{

    /**
     * @return array
     */
    public function behaviors() {
        return [
            'searchBehavior' => SearchappendBehavior::className(),
//            [
//                'class' => SearchappendBehavior::className(),
//            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['doc_id', 'doc_sec_id', 'ekis_id', 'conferenceid', 'doc_state', 'doc_format', ], 'integer'],
            [['doc_type', 'doc_subject', 'doc_description', 'doc_created', 'doc_lider_fam', 'doc_lider_name', 'doc_lider_otch', 'doc_lider_email', 'doc_lider_phone', 'doc_lider_org', 'doc_lider_group', 'doc_lider_position', 'doc_lider_lesson', 'doc_comment', ], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $aDop = [])
    {
        $query = Doclad::find();
        $aWith = [];
        if( isset($aDop['with']) ) {
            $aWith = $aDop['with'];
            unset($aDop['with']);
        }
        $query->with(
            Yii::$app->user->can(User::USER_GROUP_MODERATOR) ?
                array_merge(['section', 'section.conference', 'files', ], $aWith) : // 'persons', 'members',
                array_merge(['section', 'section.conference', 'files', ], $aWith)
        );
        $query->joinWith(['section', 'section.conference', ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $sFormname = $this->formName();
        if( !isset($params[$sFormname]) ) {
            $params[$sFormname] = [];
        }
        $params[$sFormname] = array_merge($params[$sFormname], $aDop);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $aFilters = [
            'doc_id' => $this->doc_id,
            'doc_sec_id' => $this->doc_sec_id,
            'doc_created' => $this->doc_created,
            'ekis_id' => $this->ekis_id,
            'doc_state' => $this->doc_state,
            'doc_format' => $this->doc_format,
        ];

        if( !Yii::$app->user->can(User::USER_GROUP_MODERATOR) ) {
            $aFilters['doc_us_id'] = Yii::$app->user->getId();
        }
        else {
            /** @var User $obUser */
            if( empty($this->conferenceid) ) {
                // этот if тут для того, чтобы модераторы секций могли выгрузить вообще все доклады из конференции
                // потому что из-за их изголяния с разделением на персональных участников и от организаций, и разделением секций
                // по этому признаку, ранее зарегистрированные доклады перехолдят в другие секции и их невоможно увидеть в нужной секции
                // в контроллере я сюда ($this->conferenceid) загружаю конференции, в которых юзер главный модератор
                $obUser = Yii::$app->user->identity;
                if( !empty($obUser->sectionids) ) {
                    $aFilters = [
                        'doc_sec_id' => $obUser->sectionids,
                    ];
                }
            }
        }

        if( $this->conferenceid ) {
            $query->andFilterWhere([ Conference::tableName() . '.cnf_id' => $this->conferenceid, ]);
        }

        $query->andFilterWhere($aFilters);

        if( !empty($this->doc_lider_fam) ) {
            $query->andFilterWhere(['like', 'doc_lider_fam', $this->doc_lider_fam])
                ->andFilterWhere(['like', 'doc_lider_name', $this->doc_lider_name])
                ->andFilterWhere(['like', 'doc_lider_otch', $this->doc_lider_otch])
                ->andFilterWhere(['like', 'doc_lider_email', $this->doc_lider_email])
                ->andFilterWhere(['like', 'doc_lider_phone', $this->doc_lider_phone]);
        }

        $query->andFilterWhere(['like', 'doc_type', $this->doc_type])
            ->andFilterWhere(['like', 'doc_subject', $this->doc_subject])
            ->andFilterWhere(['like', 'doc_description', $this->doc_description])
//            ->andFilterWhere(['like', 'doc_lider_fam', $this->doc_lider_fam])
//            ->andFilterWhere(['like', 'doc_lider_name', $this->doc_lider_name])
//            ->andFilterWhere(['like', 'doc_lider_otch', $this->doc_lider_otch])
//            ->andFilterWhere(['like', 'doc_lider_email', $this->doc_lider_email])
//            ->andFilterWhere(['like', 'doc_lider_phone', $this->doc_lider_phone])
            ->andFilterWhere(['like', 'doc_lider_org', $this->doc_lider_org])
            ->andFilterWhere(['like', 'doc_lider_group', $this->doc_lider_group])
//            ->andFilterWhere(['like', 'doc_lider_level', $this->doc_lider_level])
            ->andFilterWhere(['like', 'doc_lider_position', $this->doc_lider_position])
            ->andFilterWhere(['like', 'doc_lider_lesson', $this->doc_lider_lesson]);

        return $dataProvider;
    }

}
