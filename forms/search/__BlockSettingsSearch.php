<?php

namespace thefx\blocks\forms\search;

use thefx\blocks\models\blocks\BlockSettings;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BlockSettingsSearch represents the model behind the search form of `app\shop\entities\Block\BlockSettings`.
 */
class BlockSettingsSearch extends BlockSettings
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'block_id', 'photo_crop_width', 'photo_crop_height', 'photo_crop_type', 'photo_preview_crop_width', 'photo_preview_crop_height', 'photo_preview_crop_type'], 'integer'],
            [['upload_path'], 'safe'],
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
        $query = BlockSettings::find();

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
            'block_id' => $this->block_id,
            'photo_crop_width' => $this->photo_crop_width,
            'photo_crop_height' => $this->photo_crop_height,
            'photo_crop_type' => $this->photo_crop_type,
            'photo_preview_crop_width' => $this->photo_preview_crop_width,
            'photo_preview_crop_height' => $this->photo_preview_crop_height,
            'photo_preview_crop_type' => $this->photo_preview_crop_type,
        ]);

        $query->andFilterWhere(['like', 'upload_path', $this->upload_path]);

        return $dataProvider;
    }
}
