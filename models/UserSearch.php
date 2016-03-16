<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

use app\models\User;
use app\models\Usersection;

/**
 * UserSearch represents the model behind the search form about `app\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['us_id', 'us_active', 'us_mainmoderator', ], 'integer'],
            [['us_description'], 'string', ],
            [['us_group', 'us_email', 'us_pass', 'us_created', 'us_confirmkey', 'us_key', 'sectionids'], 'safe'],
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
        $query = User::find()
            ->with(['sections', 'sections.section', ]);

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
            'us_id' => $this->us_id,
            'us_active' => $this->us_active,
            'us_created' => $this->us_created,
//            'us_mainmoderator' => $this->us_mainmoderator,
        ]);


        if( !empty($this->sectionids) || !empty($this->us_mainmoderator) ) {
            $ansQuery = (new Query)
                ->select('usec_user_id')
                ->from(Usersection::tableName())
                ->andFilterWhere(['usec_section_id' => $this->sectionids, 'usec_section_primary' => $this->us_mainmoderator, ])
                ->distinct();
            $query->andFilterWhere(['us_id' => $ansQuery]);
        }


        $query->andFilterWhere(['like', 'us_group', $this->us_group])
            ->andFilterWhere(['or', ['like', 'us_email', $this->us_email], ['like', 'us_name', $this->us_email], ])
            ->andFilterWhere(['like', 'us_pass', $this->us_pass])
            ->andFilterWhere(['like', 'us_confirmkey', $this->us_confirmkey])
            ->andFilterWhere(['like', 'us_key', $this->us_key]);

        return $dataProvider;
    }
}
