<?php

namespace thefx\blocks\controllers;

use thefx\blocks\models\Block;
use thefx\blocks\models\forms\BlockFieldsForm;
use Yii;
use yii\web\Controller;

/**
 * BlockTranslateController implements the CRUD actions for BlockTranslate model.
 */
class BlockFieldsController extends Controller
{
    public function actionUpdate($block_id, $type)
    {
        $block = Block::find()
            ->where(['id' => $block_id])
            ->with([
                'fields.property',
                'fields.children.property'
            ])
            ->one();

        $form = new BlockFieldsForm($block, $type);

        if ($this->request->isPost && $form->load(Yii::$app->request->post()) && $form->save()) {
            Yii::$app->session->setFlash('success', 'Поля сохранены');
            return $this->redirect(['block-sections/index', 'block_id' => $block_id]);
        }

        return $this->render('update', [
            'model' => $form,
            'block' => $block,
            'template' => $form->getDefaultTemplate(),
        ]);
    }
}
