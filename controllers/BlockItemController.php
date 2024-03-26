<?php

namespace thefx\blocks\controllers;

use thefx\blocks\models\blocks\Block;
use thefx\blocks\models\blocks\BlockCategory;
use thefx\blocks\models\blocks\BlockItem;
use thefx\blocks\models\blocks\BlockItemPropAssignments;
use thefx\blocks\models\blocks\BlockProp;
use thefx\blocks\models\files\Files;
use thefx\blocks\models\images\Images;
use thefx\blocks\services\TransactionManager;
use Yii;
use yii\base\Module;
use yii\caching\TagDependency;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * BlockItemController implements the CRUD actions for BlockItem model.
 */
class BlockItemController extends Controller
{
    public $transaction;

    public function __construct($id, Module $module, TransactionManager $transaction, array $config = [])
    {
        $this->transaction = $transaction;
        parent::__construct($id, $module, $config);
    }

    /**
     * Creates a new BlockItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $parent_id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionCreate($parent_id, $type = null, $series_id = null)
    {
        $this->layout = 'pure';

        $category = BlockCategory::findOrFail($parent_id);
        $series = $series_id ? BlockItem::findOrFail($series_id) : null;
        $block = Block::find()->with('fields')->where(['id' => $category->block_id])->one();
        $parents = $category->getParents()->all();

        $blockItemType = $type === BlockItem::TYPE_SERIES ? BlockItem::TYPE_SERIES : BlockItem::TYPE_ITEM;
        $model = BlockItem::create($block->id, $parent_id, $blockItemType);
        if ($series) {
            $model->series_id = $series->id;
        }
        $model->populateAssignments();

        if ($model->load(Yii::$app->request->post()) /*&& $model->save()*/) {
            $model->loadAssignments(Yii::$app->request->post());
            if ($model->validate()) {
                if ($series) {
                    $model->parent_id = $series->parent_id;
                }
                $model->save();
                TagDependency::invalidate(Yii::$app->cache, 'block_items_' . $category->block_id);
                Yii::$app->session->setFlash('success', $block->translate->block_item . ' добавлен');

                // add property with patent id if necessary
                $this->addPropertyWithPatentIdIfNecessary($model);

                if ($series) {
                    return $this->redirect(['block-category/index', 'series_id' => $series->id, 'parent_id' => $series->parent_id]);
                }
                return $this->redirect(['block-category/index', 'parent_id' => $parent_id]);
            }
        }

        $fieldType = $model->type === BlockItem::TYPE_SERIES ? 'fields' : 'fieldsSeries';
        $template = $block->getFieldsTemplates($fieldType);

        if ($series) {
            $template = $this->changeParentIdToSeries($template);
        }

        return $this->render('create', [
            'block' => $block,
            'category' => $category,
            'parents' => $parents,
            'model' => $model,
            'template' => $template,
            'elem' => $model->propAssignments,
        ]);
    }


    /**
     * Updates an existing BlockItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param $parent_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $parent_id)
    {
        $this->layout = 'pure';

        $model = $this->findModel($id);
        $category = BlockCategory::findOrFail($model->parent_id);
        $series = $model->series_id ? BlockItem::findOrFail($model->series_id) : null;

        $block = Block::findOrFail($category->block_id);
        $parents = $category->getParents()->all();

        $model->populateAssignments();

        if ($model->load(Yii::$app->request->post()) /*&& $model->save()*/) {
            $model->loadAssignments(Yii::$app->request->post());
            if ($model->validate()) {
                $model->setAttribute('update_user', Yii::$app->user->id);
                $model->setAttribute('update_date', date('Y-m-d H:i:s'));
                if ($model->series_id) {
                    $series2 = BlockItem::findOrFail($model->series_id);
                    $model->parent_id = $series2->parent_id;
                }
                $model->save();
                TagDependency::invalidate(Yii::$app->cache, 'block_items_' . $category->block_id);
                Yii::$app->session->setFlash('success', $block->translate->block_item . ' обновлен');

                // add property with patent id if necessary
                $this->addPropertyWithPatentIdIfNecessary($model);

                if ($series) {
                    return $this->redirect(['block-category/index', 'series_id' => $series->id, 'parent_id' => $series->parent_id]);
                }
                return $this->redirect(['block-category/index', 'parent_id' => $parent_id]);
            }
        }

        $template = $block->getFieldsTemplates($model->type);

        if ($series) {
            $template = $this->changeParentIdToSeries($template);
        }

        return $this->render('update', [
            'block' => $block,
            'category' => $category,
            'parents' => $parents,
            'model' => $model,
            'template' => $template,
            'elem' => $model->propAssignments,
        ]);
    }

    public function actionCopy($id)
    {
        $model = $this->findModel($id);
        $newModel = new BlockItem($model->getAttributes());
        $properties = BlockProp::find()->indexBy('id')->where(['block_id' => $model->block_id])->all();

        $this->transaction->wrap(function () use ($model, $newModel, $properties) {

            $newModel->id = null;
            $newModel->title .= ' - Копия';
            $newModel->photo = $this->copyFile($model->photo);
            $newModel->photo_preview = $this->copyFile($model->photo_preview);
            $newModel->create_user = Yii::$app->user->id;
            $newModel->create_date = date('Y-m-d H:i:s');

            $newModel->save(false) or die('error');

            foreach ($model->propAssignments as $propAssignment) {

                if ($properties[$propAssignment->prop_id]->type === BlockProp::TYPE_IMAGE) {
                    $newValues = [];
                    $values  = explode(';', $propAssignment->value);
                    foreach ($values as $value) {
                        $newFilename = uniqid('', false);
                        $newValues[] = $this->copyFile($value, $newFilename);
                        $this->copyFile('prev_' . $value, 'prev_' . $newFilename);
                        $this->copyFile('square_' . $value, 'square_' . $newFilename);
                    }
                    $newValues = implode(';', $newValues);
                } else if ($properties[$propAssignment->prop_id]->type === BlockProp::TYPE_FILE) {
                    $newValues = [];
                    $values  = explode(';', $propAssignment->value);
                    foreach ($values as $value) {
                        $newValues[] = $this->copyFile($value);
                    }
                    $newValues = implode(';', $newValues);
                } else {
                    $newValues = $propAssignment->value;
                }

                $newPropAssignment = new BlockItemPropAssignments($propAssignment->getAttributes());
                $newPropAssignment->id = null;
                $newPropAssignment->block_item_id = $newModel->id;
                $newPropAssignment->value = $newValues;
                $newPropAssignment->save(false) or die('error 2'); // todo: проверить сохранение
            }
        });

        return $this->redirect(['update', 'id' => $newModel->id, 'parent_id' => $model->parent_id]);
    }

    public function actionDeletePhoto($id, $field)
    {
        $model = $this->findModel($id);
        (new Images())->removeImage($model->{$field});
        $model->updateAttributes([$field => null]);
        TagDependency::invalidate(Yii::$app->cache, 'block_items_' . $model->block_id);
        return $this->redirect(['update', 'id' => $id, 'parent_id' => $model->parent_id]);
    }

    /**
     * Удаление фото из характеристики
     *
     * @param $id
     * @param $name
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionDeletePhotoProp($id, $name)
    {
        $model = BlockItemPropAssignments::findOrFail($id);
        $model->deletePhoto($name);
        $modelItem = $this->findModel($model->block_item_id);
        TagDependency::invalidate(Yii::$app->cache, 'block_items_' . $modelItem->block_id);
        return $this->redirect(['update', 'id' => $model->blockItem->id, 'parent_id' => $model->blockItem->parent_id]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionDeleteFileProp($id, $name)
    {
        $model = BlockItemPropAssignments::findOrFail($id);
        $model->deleteFile($name);
        $modelItem = $this->findModel($model->block_item_id);
        TagDependency::invalidate(Yii::$app->cache, 'block_items_' . $modelItem->block_id);
        return $this->redirect(['update', 'id' => $model->blockItem->id, 'parent_id' => $model->blockItem->parent_id]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionSortPhotoProp($id)
    {
        $ids = array_filter(Yii::$app->request->post('ids', []));

        $model = BlockItemPropAssignments::findOrFail($id);

        if (!empty($ids) && $model !== null) {
            $value = implode(';', $ids);
            $model->value = $value;
            $model->save(false) or die(print_r($model->getErrors()));
            $modelItem = $this->findModel($model->block_item_id);
            TagDependency::invalidate(Yii::$app->cache, 'block_items_' . $modelItem->block_id);
            die('done');
        }
    }

    /**
     * Deletes an existing BlockItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $block = Block::findOrFail($model->block_id);
        $parentId = $model->parent_id;
        $elementName = $block->translate->block_item;
        TagDependency::invalidate(Yii::$app->cache, 'block_items_' . $model->block_id);
        $model->delete();
        Yii::$app->session->setFlash('success', $elementName . ' удален');
        return $this->redirect(['block-category/index', 'parent_id' => $parentId]);
    }

    public function actionGetFileInfo($filename)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = Files::findOne(['file' => $filename]);

        return [
            'result' => 'success',
            'model' => $model
        ];
    }

    public function actionEditFileInfo()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = Files::findOne(['file' => $_POST['Files']['file']]);

        if ($model) {
            $model->load(Yii::$app->request->post());
            $model->save();
        }

        return [
            'result' => 'success',
            'model' => $model,
        ];
    }

    private function copyFile($filename, $newFilename = '')
    {
        if (!$filename) {
            return '';
        }
        $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);
        $newFilename = $newFilename ?: uniqid('', false);
        $newFilename .= '.' . $fileExtension;

//        $fileSrc = Url::to('/upload/blocks/', true) . $filename;
        $fileSrc = $_SERVER['DOCUMENT_ROOT'] . '/upload/blocks/' . $filename;
        $fileDest = $_SERVER['DOCUMENT_ROOT'] . '/upload/blocks/' . $newFilename;

        try {
            if (copy($fileSrc, $fileDest)) {
                // echo "Copy success!";

                $file = new Files([
                    'file' => $newFilename,
                    'title' => $newFilename,
                ]);

                $file->save();

                return $newFilename;
            }
            // echo "Copy failed.";
        } catch (\yii\base\ErrorException $e) {
            // echo "Copy failed.";
        }
        return '';
    }

    /**
     * Finds the BlockItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BlockItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BlockItem::find()->with([
                'propAll.elements', // when populates
                'propAssignments.prop',
                'propAssignments.prop.block.settings',
                'propAssignments.prop.elements'
            ])->where(['id' => $id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Change parent_id field to series_id
     *
     * @param $template
     * @return array
     */
    protected function changeParentIdToSeries($template)
    {
        foreach ($template as $tabName => $tabFields) {
            foreach ($tabFields as $fieldKey => $fieldValue) {
                if ($fieldValue['type'] === 'model' && $fieldValue['value'] === 'parent_id') {
                    $template[$tabName][$fieldKey]['value'] = 'series_id';
                    $template[$tabName][$fieldKey]['name'] = 'Серия';
                }
                // parent categories todo move to project
                if ($fieldValue['type'] === 'prop' && $fieldValue['value'] === '84') {
                    unset($template[$tabName][$fieldKey]);
                }
            }
        }
        return $template;
    }

    /**
     * @param BlockItem $model
     * @return void
     */
    public function addPropertyWithPatentIdIfNecessary(BlockItem $model): void
    {
        // Только для каталога и не родительской категории
        if ($model->block_id != 1 || $model->parent_id == 1 || $model->series_id != '') {
            return;
        }

        $assignment = BlockItemPropAssignments::findOne(['prop_id' => 84, 'block_item_id' => $model->id]);

        if ($assignment) {
            $value = explode(';', $assignment->value);
            $value[] = $model->parent_id;
            $value = array_unique($value);
            $assignment->value = implode(';', $value);
            $assignment->save() or die('1111');
            return;
        }
        $assignment = new BlockItemPropAssignments([
            'block_item_id' => $model->id,
            'prop_id' => 84,
            'value' => $model->parent_id,
        ]);
        $assignment->save() or die('2222');
    }
}
