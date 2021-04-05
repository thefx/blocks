<?php

namespace thefx\blocks\forms\search;

use thefx\blocks\models\blocks\BlockProp;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BlockPropSearch represents the model behind the search form of `app\shop\entities\Block\BlockProp`.
 */
class BlockPropSearch extends BlockProp
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'block_id', 'public', 'multi', 'required', 'sort', 'in_filter'], 'integer'],
            [['title', 'type', 'code', 'hint'], 'safe'],
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
        $query = BlockProp::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['sort' => SORT_ASC, 'id' => SORT_ASC]],
            'pagination' => false,
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
            'block_id' => $this->block_id,
            'public' => $this->public,
            'multi' => $this->multi,
            'required' => $this->required,
            'sort' => $this->sort,
            'in_filter' => $this->in_filter,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'hint', $this->hint]);

        return $dataProvider;
    }
}
