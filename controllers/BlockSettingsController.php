<?php

namespace thefx\blocks\controllers;

use thefx\blocks\models\blocks\BlockSettings;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * BlockSettingsController implements the CRUD actions for BlockSettings model.
 */
class BlockSettingsController extends Controller
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
     * @return BlockSettings
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = BlockSettings::find()->where(['block_id' => $id])->one()) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
