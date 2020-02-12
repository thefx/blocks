<?php

namespace thefx\blocks\forms\search;

use thefx\blocks\models\blocks\BlockTranslate;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BlockTranslateSearch represents the model behind the search form of `app\shop\entities\Block\BlockTranslate`.
 */
class BlockTranslateSearch extends BlockTranslate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'block_id'], 'integer'],
            [['category', 'categories', 'block', 'blocks', 'block_create', 'block_update', 'block_delete', 'category_create', 'category_update', 'category_delete'], 'safe'],
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
        $query = BlockTranslate::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'block_id' => $this->block_id,
        ]);

        $query->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'categories', $this->categories])
            ->andFilterWhere(['like', 'block', $this->block])
            ->andFilterWhere(['like', 'blocks', $this->blocks])
            ->andFilterWhere(['like', 'block_create', $this->block_create])
            ->andFilterWhere(['like', 'block_update', $this->block_update])
            ->andFilterWhere(['like', 'block_delete', $this->block_delete])
            ->andFilterWhere(['like', 'category_create', $this->category_create])
            ->andFilterWhere(['like', 'category_update', $this->category_update])
            ->andFilterWhere(['like', 'category_delete', $this->category_delete]);

        return $dataProvider;
    }
}
