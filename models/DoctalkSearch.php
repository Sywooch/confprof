<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Doctalk;

/**
 * DoctalkSearch represents the model behind the search form about `app\models\Doctalk`.
 */
class DoctalkSearch extends Doctalk
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dtlk_id', 'dtlk_us_id', 'dtlk_doc_id'], 'integer'],
            [['dtlk_text', 'dtlk_created'], 'safe'],
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
        $query = Doctalk::find();
        $query->with('autor');

        $aProvider = [
            'query' => $query,
            'sort'=> [
                'defaultOrder' => isset($params['sort']) ? $params['sort'] : ['dtlk_id' => SORT_ASC],
            ],
//            'pagination' => [
//                'pageSize' => 4,
//            ],

        ];

        $dataProvider = new ActiveDataProvider($aProvider);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'dtlk_id' => $this->dtlk_id,
            'dtlk_us_id' => $this->dtlk_us_id,
            'dtlk_doc_id' => $this->dtlk_doc_id,
            'dtlk_created' => $this->dtlk_created,
        ]);

        $query->andFilterWhere(['like', 'dtlk_text', $this->dtlk_text]);

        return $dataProvider;
    }
}
