<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\LocationHistory;

/**
 * LocationHistorySearch represents the model behind the search form about `common\models\LocationHistory`.
 */
class LocationHistorySearch extends LocationHistory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'beacon_id', 'locator_id', 'user_id'], 'integer'],
            [['longitude', 'latitude'], 'number'],
            [['created_at'], 'safe'],
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
        $query = LocationHistory::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                    'id' => SORT_DESC
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'beacon_id' => $this->beacon_id,
            'locator_id' => $this->locator_id,
            'user_id' => $this->user_id,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'created_at' => $this->created_at,
        ]);

        return $dataProvider;
    }
}
