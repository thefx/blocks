<?php

namespace thefx\blocks\models\forms\search;

use thefx\blocks\models\BlockItem;
use thefx\blocks\models\BlockSection;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\db\Query;

class BlockSectionSearch extends BlockSection
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'block_id', 'section_id', 'left', 'right', 'depth', 'create_user', 'update_user', 'public', 'sort'], 'integer'],
            [['title', 'alias', 'anons', 'text', 'photo', 'photo_preview', 'date', 'create_date', 'update_date'], 'safe'],
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
        $commonFields = [
            'id',
            'title',
            'anons',
            'text',
            'section_id',
            'block_id',
            'public',
            'anons',
//            'date',
            'create_user',
            'create_date',
            'update_user',
            'update_date',
            'photo_preview',
            'photo',
            'sort',
        ];

        $this->load($params);

        $query1 = (new Query())
            ->select(array_merge($commonFields, [ new Expression('"folder" as type') ], ['`left`']))
            ->from(BlockSection::tableName());

        $query2 = (new Query())
            ->select(array_merge($commonFields, [ new Expression('"item" as type') ], ['`sort` AS `left`']))
            ->from(BlockItem::tableName());

        $unionQuery = BlockSection::find()
            ->from($query1->union($query2));

        if ($this->section_id) {
            $unionQuery->andFilterWhere(['section_id' => $this->section_id]);
        } else {
            $unionQuery->andFilterWhere(['block_id' => $this->block_id, 'section_id' => 0]);
        }

        $unionQuery->andFilterWhere(['or',
            ['like', 'anons', trim($this->title)],
            ['like', 'title', trim($this->title)],
            ['like', 'text', trim($this->title)]]);

        $dataProvider = new ActiveDataProvider([
            'query' => $unionQuery,
//            'pagination' => [
//                'pageSize' => 20,
//            ],
//            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'type' => SORT_ASC,
                    'update_date' => SORT_DESC,
//                    'create_date' => SORT_DESC,
//                    'id' => SORT_ASC
                ],
                'attributes' => [
                    'id',
//                    'update_date',
                    'update_date' => [
                        'asc' => ['IFNULL(update_date,create_date)' => SORT_ASC],
                        'desc' => ['IFNULL(update_date,create_date)' => SORT_DESC],
                    ],
//                    'create_date',
                    'public',
                    'title',
                    'anons',
                    'date',
                    'sort',
                    'left',
                    'type',
                ],
            ]
        ]);

        return $dataProvider;
    }
}
