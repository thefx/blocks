<?php

namespace thefx\blocks\controllers;

use thefx\blocks\models\blocks\BlockItem;
use Yii;
use yii\caching\TagDependency;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class BlockCategoryTasksController extends Controller
{
    /**
     * @throws NotFoundHttpException
     */
    public function actionMove($categoryId, array $item = [])
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $blockItems = BlockItem::find()->where(['id' => $item])->all();

        foreach ($blockItems as $blockItem) {
            $blockItem->parent_id = (int) $categoryId;
            $blockItem->series_id = null;
            $blockItem->save() or die(var_dump($blockItem->getErrors()));

            // change parent_id for all items of series
            if ($blockItem->type === BlockItem::TYPE_SERIES) {
                $itemsOfSeries = BlockItem::find()->where(['series_id' => $blockItem->id])->all();

                foreach ($itemsOfSeries as $item2) {
                    $item2->parent_id = $blockItem->parent_id;
                    $item2->save() or die(var_dump($blockItem->getErrors()));
                }
            }
        }

        TagDependency::invalidate(Yii::$app->cache, 'block_items_' . $blockItem->block_id);

        return [
            'result' => 'success'
        ];
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionMoveSeries($seriesId, array $item = [])
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $series = BlockItem::findOrFail(['id' => $seriesId, 'type' => BlockItem::TYPE_SERIES]);
        $blockItems = BlockItem::find()->where(['id' => $item])->all();

        foreach ($blockItems as $blockItem) {
            if ($blockItem->type === BlockItem::TYPE_ITEM) {
                $blockItem->series_id = $series->id;
                $blockItem->parent_id = $series->parent_id;
                $blockItem->save() or die(var_dump($blockItem->getErrors()));
            }
        }

        TagDependency::invalidate(Yii::$app->cache, 'block_items_' . $blockItem->block_id);

        return [
            'result' => 'success'
        ];
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionActivate($categoryId, array $item = [])
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $blockItems = BlockItem::find()->where(['id' => $item])->all();

        foreach ($blockItems as $blockItem) {
            $blockItem->public = 1;
            $blockItem->save() or die(var_dump($blockItem->getErrors()));
        }

        TagDependency::invalidate(Yii::$app->cache, 'block_items_' . $blockItem->block_id);

        return [
            'result' => 'success'
        ];
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionDeactivate($categoryId, array $item = [])
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $blockItems = BlockItem::find()->where(['id' => $item])->all();

        foreach ($blockItems as $blockItem) {
            $blockItem->public = 0;
            $blockItem->save() or die(var_dump($blockItem->getErrors()));
        }

        TagDependency::invalidate(Yii::$app->cache, 'block_items_' . $blockItem->block_id);

        return [
            'result' => 'success'
        ];
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionDelete($categoryId, array $item = [])
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $blockItems = BlockItem::find()->where(['id' => $item])->all();

        foreach ($blockItems as $blockItem) {
            $blockItem->delete() or die(var_dump($blockItem->getErrors()));
        }

        TagDependency::invalidate(Yii::$app->cache, 'block_items_' . $blockItem->block_id);

        return [
            'result' => 'success'
        ];
    }
}
