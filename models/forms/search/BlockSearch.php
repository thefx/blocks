<?php

namespace thefx\blocks\models\forms\search;

use thefx\blocks\models\Block;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BlockSearch represents the model behind the search form of `app\shop\entities\Block\Block`.
 */
class BlockSearch extends Model
{
    public $id;
    public $title;
    public $alias;
    public $table;
    public $template;
    public $pagination;
    public $create_user;
    public $update_user;
    public $create_date;
    public $update_date;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'create_user', 'update_user'], 'integer'],
            [['title', 'alias', 'table', 'template', 'pagination', 'create_date', 'update_date'], 'safe'],
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
        $query = Block::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['sort' => SORT_ASC, 'id' => SORT_ASC]]
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
            'create_user' => $this->create_user,
            'create_date' => $this->create_date,
            'update_user' => $this->update_user,
            'update_date' => $this->update_date,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'table', $this->table])
            ->andFilterWhere(['like', 'template', $this->template])
            ->andFilterWhere(['like', 'pagination', $this->pagination]);

        return $dataProvider;
    }
}
