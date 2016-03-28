<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Conference;

/**
 * ConferenceSearch represents the model behind the search form about `app\models\Conference`.
 */
class ConferenceSearch extends Conference
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cnf_id', 'cnf_guestlimit', ], 'integer'],
            [['cnf_title', 'cnf_class', 'cnf_controller', 'cnf_description', 'cnf_created'], 'safe'],
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
        $query = Conference::find();

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
            'cnf_id' => $this->cnf_id,
            'cnf_created' => $this->cnf_created,
        ]);

        $query->andFilterWhere(['like', 'cnf_title', $this->cnf_title])
            ->andFilterWhere(['like', 'cnf_class', $this->cnf_class])
            ->andFilterWhere(['like', 'cnf_controller', $this->cnf_controller])
            ->andFilterWhere(['like', 'cnf_description', $this->cnf_description]);

        return $dataProvider;
    }
}
