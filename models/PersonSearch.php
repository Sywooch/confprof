<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Person;

/**
 * PersonSearch represents the model behind the search form about `app\models\Person`.
 */
class PersonSearch extends Person
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prs_id', 'prs_active', 'prs_type', 'prs_sec_id', 'prs_doc_id', 'ekis_id'], 'integer'],
            [['prs_fam', 'prs_name', 'prs_otch', 'prs_email', 'prs_phone', 'prs_org', 'prs_group', 'prs_level', 'prs_position', 'prs_lesson'], 'safe'],
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
        $query = Person::find();

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
            'prs_id' => $this->prs_id,
            'prs_active' => $this->prs_active,
            'prs_type' => $this->prs_type,
            'prs_sec_id' => $this->prs_sec_id,
            'prs_doc_id' => $this->prs_doc_id,
            'ekis_id' => $this->ekis_id,
        ]);

        $query->andFilterWhere(['like', 'prs_fam', $this->prs_fam])
            ->andFilterWhere(['like', 'prs_name', $this->prs_name])
            ->andFilterWhere(['like', 'prs_otch', $this->prs_otch])
            ->andFilterWhere(['like', 'prs_email', $this->prs_email])
            ->andFilterWhere(['like', 'prs_phone', $this->prs_phone])
            ->andFilterWhere(['like', 'prs_org', $this->prs_org])
            ->andFilterWhere(['like', 'prs_group', $this->prs_group])
            ->andFilterWhere(['like', 'prs_level', $this->prs_level])
            ->andFilterWhere(['like', 'prs_position', $this->prs_position])
            ->andFilterWhere(['like', 'prs_lesson', $this->prs_lesson]);

        return $dataProvider;
    }
}