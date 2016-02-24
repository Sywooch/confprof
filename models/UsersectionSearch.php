<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Usersection;

/**
 * UsersectionSearch represents the model behind the search form about `app\models\Usersection`.
 */
class UsersectionSearch extends Usersection
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['usec_id', 'usec_user_id', 'usec_section_id'], 'integer'],
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
        $query = Usersection::find();

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
            'usec_id' => $this->usec_id,
            'usec_user_id' => $this->usec_user_id,
            'usec_section_id' => $this->usec_section_id,
        ]);

        return $dataProvider;
    }
}
