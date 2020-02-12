<?php

namespace thefx\blocks\forms\search;

use thefx\blocks\models\blocks\BlockPropElem;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BlockPropElemSearch represents the model behind the search form of `app\shop\entities\Block\BlockPropElem`.
 */
class BlockPropElemSearch extends BlockPropElem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'block_prop_id', 'sort', 'default'], 'integer'],
            [['title', 'code'], 'safe'],
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
        $query = BlockPropElem::find();

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
            'block_prop_id' => $this->block_prop_id,
            'sort' => $this->sort,
            'default' => $this->default,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }
}
