<?php

namespace app\models;

use yii\data\ActiveDataProvider;

class ParameterSearch extends Parameter
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['title'], 'string'],
        ];
    }

    public function search($params)
    {
        $query = Parameter::find();

        $filtereData = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $filtereData;
        }

        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['like', 'title', $this->title]);

        return $filtereData;
    }
}