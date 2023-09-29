<?php

namespace thefx\blocks\actions;

use thefx\blocks\models\BlockPropertyElement;
use Yii;
use yii\base\Action;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class GetTagsAction extends Action
{
    public function run($q = null, $id = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $out = ['results' => ['id' => '', 'value' => '']];

        if ($q !== null) {
            $data = (new Query())
                ->select(['id', 'title'])
                ->from(BlockPropertyElement::tableName())
                ->where(['or', ['like', 'title', str_replace(' ', '%', '%' . trim($q)) . '%', false], ['like', 'id', $q]])
                ->orderBy('title ASC')
                ->limit(15)
                ->all();

            $out['results'] = ArrayHelper::getColumn($data, static function ($element) {
                return ['id' => $element['id'], 'text' => $element['title'] . ' #' . $element['id'], 'output' => $element['title']];
            });
        }
        return $out;
    }
}