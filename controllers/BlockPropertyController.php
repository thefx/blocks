<?php

namespace thefx\blocks\controllers;

use thefx\blocks\models\BlockFields;
use thefx\blocks\models\BlockProperty;
use thefx\blocks\models\BlockPropertyElement;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;

/**
 * BlockPropertyController implements the CRUD actions for BlockProperty model.
 */
class BlockPropertyController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Displays a single BlockProperty model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new BlockProperty model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $block_id
     * @return mixed
     * @throws InvalidConfigException
     */
    public function actionCreate($block_id)
    {
        $this->layout = $this->module->layoutPure;

        $model = BlockProperty::create($block_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (array_key_exists('BlockPropertyElement', Yii::$app->request->post())) {
                $sort = 1;
                foreach (Yii::$app->request->post('BlockPropertyElement') as $key => $item) {
                    $element = new BlockPropertyElement($item);
                    $element->property_id = $model->id;
                    $element->sort = $sort++;
                    $element->save();
                }
            }
            return $this->redirect(['block/view', 'id' => $model->block_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BlockProperty model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     *
     * @see https://packagist.org/packages/unclead/yii2-multiple-input
     */
    public function actionUpdate($id)
    {
        $this->layout = $this->module->layoutPure;

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            // delete BlockPropertyElement
            BlockPropertyElement::deleteAll(['property_id' => $model->id]);

            if (array_key_exists('BlockPropertyElement', Yii::$app->request->post())) {

                $oldKeys = array_column($model->elements, 'id');
                $elements = [];
                foreach (Yii::$app->request->post('BlockPropertyElement') as $key => $item) {
                    $elements[$key] = new BlockPropertyElement($item);
                    $elements[$key]->property_id = $model->id;
                }

                $model->populateRelation('elements', $elements);

                $newKeys = array_column($elements, 'id');
                $diffKeys = array_diff($oldKeys, $newKeys);
                if (!empty($diffKeys)) {
                    $elements = array_filter($elements, function ($element) use ($diffKeys) {
                        return !in_array($element->id, $diffKeys);
                    });
                }

                $sort = 1;
                foreach ($elements as $element) {
                    $element->sort = $sort++;
                    $element->save();
                }
            }

            if ($model->save()) {
                return $this->redirect(['block/view', 'id' => $model->block_id, '#' => 'tab_properties']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $index
     * @return string
     */
    public function actionGetElem($index)
    {
        $form = ActiveForm::begin();

        $elem = new BlockPropertyElement([
            'sort' => 100,
        ]);

        return $this->renderPartial('_form_elem', [
            'index' => $index,
            'form' => $form,
            'model' => $elem,
        ]);
    }

    /**
     * Deletes an existing BlockProperty model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $block_id = $model->block_id;
        BlockFields::deleteAll(['block_id' => $block_id, 'type' => 'prop', 'value' => $model->id]);
        $model->delete();
        return $this->redirect(['block/view', 'id' => $block_id]);
    }

    /**
     * Finds the BlockProperty model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BlockProperty the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BlockProperty::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
