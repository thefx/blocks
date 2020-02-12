<?php

namespace thefx\blocks\forms\search;

use thefx\blocks\models\blocks\BlockCategory;
use thefx\blocks\models\blocks\BlockItem;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\db\Query;

/**
 * BlockCategorySearch represents the model behind the search form of `app\shop\entities\Block\BlockCategory`.
 */
class BlockCategorySearch extends BlockCategory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'block_id', 'parent_id', 'lft', 'rgt', 'depth', 'create_user', 'update_user', 'public'], 'integer'],
            [['title', 'path', 'anons', 'text', 'photo', 'photo_preview', 'date', 'create_date', 'update_date'], 'safe'],
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


    public function formName()
    {
        return '';
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $commonFields = ['id', 'title', 'anons', 'text', 'parent_id', 'block_id', 'public', 'anons', 'date', 'create_user', 'create_date', 'update_user', 'update_date'];

        $this->load($params);

        $query1 = (new Query())
            ->select(array_merge($commonFields, [ new Expression('"folder" as type') ]))
            ->from(BlockCategory::tableName());

        $query2 = (new Query())
            ->select(array_merge($commonFields, [ new Expression('"item" as type') ]))
            ->from(BlockItem::tableName());

        $unionQuery = BlockCategory::find()
            ->from(['dummy_name' => $query1->union($query2)]);

        if ($this->block_id && $this->title) {
            $unionQuery->andFilterWhere(['block_id' => $this->block_id]);
        } else {
            $unionQuery->andFilterWhere(['parent_id' => $this->parent_id]);
        }

        $unionQuery->andFilterWhere(['or',
            ['like', 'anons', trim($this->title)],
            ['like', 'title', trim($this->title)],
            ['like', 'text', trim($this->title)]]);

        $dataProvider = new ActiveDataProvider([
            'query' => $unionQuery,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['type' => SORT_ASC, 'update_date' => SORT_DESC, 'id' => SORT_DESC],
                'attributes' => [
                    'id',
                    'update_date',
                    'public',
                    'title',
                    'anons',
                    'date',
                    'type' => [
                        'asc' => ['type' => SORT_ASC],
                        'desc' => ['type' => SORT_DESC],
                    ],
                ]
            ]
        ]);

        return $dataProvider;
    }

//    /**
//     * Creates data provider instance with search query applied
//     *
//     * @param array $params
//     * @return ActiveDataProvider
//     */
//    public function search2($params)
//    {
//        $commonFields = ['id', 'title', 'parent_id', 'public', 'anons', 'date', 'create_user', 'create_date', 'update_user', 'update_date'];
//
//        $query = BlockCategory::find();
//        $query->select(array_merge($commonFields, [ new Expression('"folder" as type') ]));
//
//        $query2 = BlockItem::find();
//        $query2->select(array_merge($commonFields, [ new Expression('"item" as type') ]));
//
//        $this->load($params);
//
//        if ($this->block_id && $this->title) {
//            $query2->andFilterWhere(['block_id' => $this->block_id]);
//            $query->andFilterWhere(['block_id' => $this->block_id]);
//        } else {
//            $query2->andFilterWhere(['parent_id' => $this->parent_id]);
//            $query->andFilterWhere(['parent_id' => $this->parent_id]);
//        }
//
//        $query2->andFilterWhere(['or',
//            ['like', 'anons', trim($this->title)],
//            ['like', 'title', trim($this->title)],
//            ['like', 'text', trim($this->title)]]);
//
//        $query->andFilterWhere(['or',
//            ['like', 'anons', trim($this->title)],
//            ['like', 'title', trim($this->title)],
//            ['like', 'text', trim($this->title)]]);
//
//        $query->union($query2);
//
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//            'sort' => ['defaultOrder' => ['id' => SORT_ASC]]
//        ]);
//
//        if (!$this->validate()) {
////             $query->where('0=1');
//            return $dataProvider;
//        }
//
//        return $dataProvider;
//    }
}
