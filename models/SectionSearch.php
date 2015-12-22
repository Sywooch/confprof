<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Section;

/**
 * SectionSearch represents the model behind the search form about `app\models\Section`.
 */
class SectionSearch extends Section
{
    public $conferencetitle;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sec_id'], 'integer'],
            [['sec_title', 'sec_cnf_id', 'sec_created'], 'safe'],
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
    public function search($params)
    {
        $query = Section::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'sec_id' => $this->sec_id,
            'sec_created' => $this->sec_created,
        ]);

        $query->andFilterWhere(['like', 'sec_title', $this->sec_title])
            ->andFilterWhere(['like', 'sec_cnf_id', $this->sec_cnf_id]);

        return $dataProvider;
    }
}
