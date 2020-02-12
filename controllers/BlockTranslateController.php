<?php

namespace thefx\blocks\controllers;

use thefx\blocks\models\blocks\BlockTranslate;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * BlockTranslateController implements the CRUD actions for BlockTranslate model.
 */
class BlockTranslateController extends Controller
{
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['block/view', 'id' => $model->block_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return BlockTranslate
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = BlockTranslate::find()->where(['block_id' => $id])->one()) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
