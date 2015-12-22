<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Doclad;

/**
 * DocladSearch represents the model behind the search form about `app\models\Doclad`.
 */
class DocladSearch extends Doclad
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['doc_id', 'doc_sec_id', 'ekis_id'], 'integer'],
            [['doc_type', 'doc_subject', 'doc_description', 'doc_created', 'doc_lider_fam', 'doc_lider_name', 'doc_lider_otch', 'doc_lider_email', 'doc_lider_phone', 'doc_lider_org', 'doc_lider_group', 'doc_lider_level', 'doc_lider_position', 'doc_lider_lesson'], 'safe'],
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
        $query = Doclad::find();

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
            'doc_id' => $this->doc_id,
            'doc_sec_id' => $this->doc_sec_id,
            'doc_created' => $this->doc_created,
            'ekis_id' => $this->ekis_id,
        ]);

        $query->andFilterWhere(['like', 'doc_type', $this->doc_type])
            ->andFilterWhere(['like', 'doc_subject', $this->doc_subject])
            ->andFilterWhere(['like', 'doc_description', $this->doc_description])
            ->andFilterWhere(['like', 'doc_lider_fam', $this->doc_lider_fam])
            ->andFilterWhere(['like', 'doc_lider_name', $this->doc_lider_name])
            ->andFilterWhere(['like', 'doc_lider_otch', $this->doc_lider_otch])
            ->andFilterWhere(['like', 'doc_lider_email', $this->doc_lider_email])
            ->andFilterWhere(['like', 'doc_lider_phone', $this->doc_lider_phone])
            ->andFilterWhere(['like', 'doc_lider_org', $this->doc_lider_org])
            ->andFilterWhere(['like', 'doc_lider_group', $this->doc_lider_group])
            ->andFilterWhere(['like', 'doc_lider_level', $this->doc_lider_level])
            ->andFilterWhere(['like', 'doc_lider_position', $this->doc_lider_position])
            ->andFilterWhere(['like', 'doc_lider_lesson', $this->doc_lider_lesson]);

        return $dataProvider;
    }
}
