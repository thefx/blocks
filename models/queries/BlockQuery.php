<?php

namespace thefx\blocks\models\queries;

use thefx\blocks\models\Block;
use yii\db\ActiveQuery;
use yii\web\NotFoundHttpException;

/**
 * This is the ActiveQuery class for [[thefx\blocks\models\blocks\Block]].
 *
 * @see Block
 */
class BlockQuery extends ActiveQuery
{
//    /**
//     * @inheritdoc
//     * @return Block[]|array
//     */
//    public function all($db = null)
//    {
//        return parent::all($db);
//    }
//
//    /**
//     * @inheritdoc
//     * @return Block|array|null
//     */
//    public function one($db = null)
//    {
//        return parent::one($db);
//    }
//
//    public function byAlias($alias)
//    {
//        return $this->andWhere(['alias' => $alias]);
//    }

//    /**
//     * @param $alias
//     * @return array|Block|null
//     * @throws NotFoundHttpException
//     */
//    public function oneOrFail($alias)
//    {
//        if (($model = $this->where(['alias' => $alias])->one()) !== null) {
//            return $model;
//        }
//        throw new NotFoundHttpException('Блок не найден');
//    }
}
