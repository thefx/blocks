<?php

namespace thefx\blocks\controllers;

use thefx\blocks\forms\BlockFieldsItemForm;
use thefx\blocks\forms\search\BlockItemSearch;
use thefx\blocks\models\blocks\Block;
use thefx\blocks\models\blocks\BlockCategory;
use thefx\blocks\models\blocks\BlockItem;
use thefx\blocks\models\blocks\BlockItemPropAssignments;
use thefx\blocks\models\files\Files;
use thefx\blocks\models\images\Images;
use Yii;
use yii\caching\TagDependency;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * BlockItemController implements the CRUD actions for BlockItem model.
 */
class BlockItemController extends Controller
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

    public function actions()
    {
        $id = (int) Yii::$app->request->get('id');

        return [
            'upload-image' => [
                'class' => 'vova07\imperavi\actions\UploadFileAction',
                'url' => '/upload/redactor/block-item/' . $id . '/',
                'path' => \Yii::getAlias("@webroot") . '/upload/redactor/block-item/' . $id,
                'unique' => true,
                'validatorOptions' => [
                    'maxWidth' => 2000,
                    'maxHeight' => 2000
                ]
            ],
            'get-uploaded-images' => [
                'class' => 'vova07\imperavi\actions\GetImagesAction',
                'url' => '/upload/redactor/block-item/' . $id . '/',
                'path' => \Yii::getAlias("@webroot") . '/upload/redactor/block-item/' . $id,
                'options' => ['only' => ['*.jpg', '*.jpeg', '*.png', '*.gif', '*.ico']],
            ]
        ];
    }

    /**
     * Lists all BlockItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BlockItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BlockItem model.
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
     * Creates a new BlockItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $parent_id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionCreate($parent_id)
    {
        $this->layout = $this->module->layoutPure;

        $category = BlockCategory::findOrFail($parent_id);
        $block = Block::find()->with('fields')->where(['id' => $category->block_id])->one();
        $parents = $category->getParents()->all();

        ###

        $modelFieldsForm = new BlockFieldsItemForm($block);

        if ($modelFieldsForm->load(Yii::$app->request->post()) && $modelFieldsForm->save()) {
            Yii::$app->session->setFlash('success', 'Поля сохранены');
            return $this->refresh();
        }

        ###

        $model = new BlockItem([
            'block_id' => $block->id,
            'parent_id' => $parent_id,
            'sort' => 100,
            'public' => 1,
        ]);

        $model->populateAssignments();

        if ($model->load(Yii::$app->request->post()) /*&& $model->save()*/) {
            $model->loadAssignments(Yii::$app->request->post());
            if ($model->validate()) {
                $model->setAttribute('create_user', Yii::$app->user->id);
                $model->setAttribute('create_date', date('Y-m-d H:i:s'));
                $model->save();
                TagDependency::invalidate(Yii::$app->cache, 'block_items_' . $category->block_id);
                Yii::$app->session->setFlash('success', $block->translate->block_item . ' добавлен');
                return $this->redirect(['block-category/index', 'parent_id' => $parent_id]);
            }
        }

        return $this->render('create', [
            'block' => $block,
            'category' => $category,
            'parents' => $parents,
            'model' => $model,
            'elem' => $model->propAssignments,
            'modelFieldsForm' => $modelFieldsForm,
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
        $this->layout = $this->module->layoutPure;

        $model = $this->findModel($id);
        $category = BlockCategory::findOrFail($model->parent_id);
        $block = Block::findOrFail($category->block_id);
        $parents = $category->getParents()->all();

        ###

        $modelFieldsForm = new BlockFieldsItemForm($block);

        if ($modelFieldsForm->load(Yii::$app->request->post()) && $modelFieldsForm->validate() && $modelFieldsForm->save()) {
            Yii::$app->session->setFlash('success', 'Поля сохранены');
            return $this->refresh();
        }

        ###

        $model->populateAssignments();

        if ($model->load(Yii::$app->request->post()) /*&& $model->save()*/) {
            $model->loadAssignments(Yii::$app->request->post());
            if ($model->validate()) {
                $model->setAttribute('update_user', Yii::$app->user->id);
                $model->setAttribute('update_date', date('Y-m-d H:i:s'));
                $model->save();
                TagDependency::invalidate(Yii::$app->cache, 'block_items_' . $category->block_id);
                Yii::$app->session->setFlash('success', $block->translate->block_item . ' обновлен');
                return $this->redirect(['block-category/index', 'parent_id' => $parent_id]);
            }
        }

        return $this->render('update', [
            'block' => $block,
            'category' => $category,
            'parents' => $parents,
            'model' => $model,
            'elem' => $model->propAssignments,
            'modelFieldsForm' => $modelFieldsForm,
        ]);
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
}
