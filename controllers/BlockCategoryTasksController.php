<?php

namespace thefx\blocks\controllers;

use thefx\blocks\models\blocks\BlockCategory;
use thefx\blocks\models\blocks\BlockItem;
use thefx\blocks\models\blocks\BlockItemPropAssignments;
use Yii;
use yii\caching\TagDependency;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class BlockCategoryTasksController extends Controller
{
    public function beforeAction($action)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionMoveToCategory($categoryId, array $item = [])
    {
        $category = BlockCategory::findOrFail(['id' => $categoryId]);
        $blockItems = BlockItem::find()->where(['id' => $item])->all();

        BlockItem::updateAll(['series_id' => null, 'parent_id' => $category->id], ['id' => $item]);

        // todo update property 84

        foreach ($blockItems as $blockItem) {
            // change parent_id for all items of series
            if ($blockItem->type === BlockItem::TYPE_SERIES) {
                BlockItem::updateAll(['parent_id' => $blockItem->parent_id], ['series_id' => $blockItem->id]);
            }
        }

        TagDependency::invalidate(Yii::$app->cache, 'block_items_' . $category->block_id);

        return [
            'result' => 'success'
        ];
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionMoveToSeries($seriesId, array $item = [])
    {
        $series = BlockItem::findOrFail(['id' => $seriesId, 'type' => BlockItem::TYPE_SERIES]);

        BlockItem::updateAll(['series_id' => $series->id, 'parent_id' => $series->parent_id], ['id' => $item, 'type' => BlockItem::TYPE_ITEM]);
        // delete categories (property 84)
        BlockItemPropAssignments::deleteAll(['block_item_id' => $item, 'prop_id' => 84]);
        TagDependency::invalidate(Yii::$app->cache, 'block_items_' . $series->block_id);

        return [
            'result' => 'success'
        ];
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionActivate($categoryId, array $item = [])
    {
        $category = BlockCategory::findOrFail(['id' => $categoryId]);

        BlockItem::updateAll(['public' => 1], ['id' => $item]);
        TagDependency::invalidate(Yii::$app->cache, 'block_items_' . $category->block_id);

        return [
            'result' => 'success'
        ];
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionDeactivate($categoryId, array $item = [])
    {
        $category = BlockCategory::findOrFail(['id' => $categoryId]);

        BlockItem::updateAll(['public' => 0], ['id' => $item]);
        TagDependency::invalidate(Yii::$app->cache, 'block_items_' . $category->block_id);

        return [
            'result' => 'success'
        ];
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionDelete($categoryId, array $item = [])
    {
        $category = BlockCategory::findOrFail(['id' => $categoryId]);

        BlockItem::deleteAll(['id' => $item]);
        TagDependency::invalidate(Yii::$app->cache, 'block_items_' . $category->block_id);

        return [
            'result' => 'success'
        ];
    }
}
