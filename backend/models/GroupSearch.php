<?php

namespace yuncms\group\backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yuncms\group\models\Group;

/**
 * GroupSearch represents the model behind the search form about `yuncms\group\models\Group`.
 */
class GroupSearch extends Group
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'billing_cycle', 'days_free', 'allow_publish', 'applicants', 'status', 'blocked_at', 'created_at', 'updated_at'], 'integer'],
            [['name', 'logo', 'introduce'], 'safe'],
            [['price'], 'number'],
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
        $query = Group::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'user_id' => $this->user_id,
            'price' => $this->price,
            'billing_cycle' => $this->billing_cycle,
            'days_free' => $this->days_free,
            'allow_publish' => $this->allow_publish,
            'applicants' => $this->applicants,
            'status' => $this->status,
            'blocked_at' => $this->blocked_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'introduce', $this->introduce]);

        return $dataProvider;
    }
}
