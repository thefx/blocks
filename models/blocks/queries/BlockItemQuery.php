<?php

namespace thefx\blocks\models\blocks\queries;

use thefx\blocks\models\blocks\BlockItem;
use yii\db\ActiveQuery;
use yii\web\NotFoundHttpException;

/**
 * This is the ActiveQuery class for [[\thefx\blocks\models\blocks\BlockItem]].
 *
 * @see \thefx\blocks\models\blocks\BlockItem
 */
class BlockItemQuery extends ActiveQuery
{
    public function active()
    {
        return $this->andWhere('[[public]]=1');
    }

    /**
     * @inheritdoc
     * @return BlockItem[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return BlockItem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param $alias
     * @param $blockAlias
     * @return BlockItem|array|null
     * @throws NotFoundHttpException
     */
    public function oneByAliasAndBlock($alias, $blockAlias)
    {
        $model = BlockItem::find()
            ->alias('i')
            ->joinWith(['block' => function(ActiveQuery $q) use ($blockAlias) {
                $q->alias('b');
                $q->where(['b.path' => $blockAlias]);
            }])
            ->with(['propAssignments.prop'])
            ->filterWhere(['i.path' => $alias])
            ->one();

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Страница не найдена.');
    }

    public function findByProp($blockId, $propName, $propValue)
    {
        return $this->alias('t')
            ->select('t.id')
            ->where(['t.block_id' => $blockId])
            ->joinWith([
                'propAssignments' => function(ActiveQuery $q) use ($propValue, $propName) {
                    $q->andWhere(['OR',
                        ['like', 'value', $propValue . ';%'],
                        ['like', 'value', '%;' . $propValue],
                        ['like', 'value', '%;' . $propValue . ';%'],
                        ['=', 'value', $propValue],
                    ]);
                    $q->joinWith(['prop' => function(ActiveQuery $q) use ($propName) {
                        $q->where(['code' => $propName]);
                    }]);
                }
        ]);
    }

    public function findByPropElemCode($blockId, $elemCode)
    {
        return $this->alias('t')
            ->select('t.id')
            ->where(['t.block_id' => $blockId])
            ->joinWith([
                'propAssignments' => function(ActiveQuery $q) use ($elemCode) {
                    $q->joinWith(['propElementAssign pe' => function(ActiveQuery $q) use ($elemCode) {
                        $q->where(['pe.code' => $elemCode]);
                    }]);
                }
            ]);
    }
}
