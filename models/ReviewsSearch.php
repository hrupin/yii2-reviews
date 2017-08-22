<?php

namespace hrupin\reviews\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use hrupin\reviews\models\Reviews;

/**
 * ReviewsSearch represents the model behind the search form about `hrupin\reviews\models\Reviews`.
 */
class ReviewsSearch extends Reviews
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reviews_id', 'reviews_child', 'status', 'reviews_parent', 'user_id', 'level', 'rating', 'date_create', 'date_update'], 'integer'],
            [['page', 'type', 'data', 'text'], 'safe'],
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
        $query = Reviews::find();

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
            'reviews_id' => $this->reviews_id,
            'reviews_child' => $this->reviews_child,
            'reviews_parent' => $this->reviews_parent,
            'user_id' => $this->user_id,
            'level' => $this->level,
            'rating' => $this->raiting,
            'status' => $this->status,
            'date_create' => $this->date_create,
            'date_update' => $this->date_update,
        ]);

        $query->andFilterWhere(['like', 'page', $this->page])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'data', $this->data])
            ->andFilterWhere(['like', 'text', $this->text]);

        return $dataProvider;
    }
}
