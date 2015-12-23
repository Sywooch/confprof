<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Docmedal;

/**
 * DocmedalSearch represents the model behind the search form about `app\models\Docmedal`.
 */
class DocmedalSearch extends Docmedal
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mdl_id', 'mdl_doc_id'], 'integer'],
            [['mdl_competition', 'mdl_title'], 'safe'],
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
        $query = Docmedal::find();

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
            'mdl_id' => $this->mdl_id,
            'mdl_doc_id' => $this->mdl_doc_id,
        ]);

        $query->andFilterWhere(['like', 'mdl_competition', $this->mdl_competition])
            ->andFilterWhere(['like', 'mdl_title', $this->mdl_title]);

        return $dataProvider;
    }
}
