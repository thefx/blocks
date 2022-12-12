<?php

namespace thefx\blocks\controllers;

use thefx\blocks\forms\BlockFieldsCategoryForm;
use thefx\blocks\forms\BlockFieldsItemForm;
use thefx\blocks\forms\search\BlockCategorySearch;
use thefx\blocks\models\blocks\Block;
use thefx\blocks\models\blocks\BlockCategory;
use thefx\blocks\models\blocks\BlockItem;
use thefx\blocks\models\images\Images;
use Yii;
use yii\caching\TagDependency;
use yii\db\Expression;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * BlockCategoryController implements the CRUD actions for BlockCategory model.
 */
class BlockCategoryController extends Controller
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
        return [
            'upload-image' => [
                'class' => 'vova07\imperavi\actions\UploadFileAction',
                'url' => '/statics/',
                'path' => \Yii::getAlias("@webroot") . '/statics',
                'unique' => true,
                'validatorOptions' => [
                    'maxWidth' => 2000,
                    'maxHeight' => 2000
                ]
            ],
            'get-uploaded-images' => [
                'class' => 'vova07\imperavi\actions\GetImagesAction',
                'url' => '/statics/',
                'path' => \Yii::getAlias("@webroot") . '/statics',
                'options' => ['only' => ['*.jpg', '*.jpeg', '*.png', '*.gif', '*.ico']],
            ]
//            'file-upload' => [
//                'class' => 'vova07\imperavi\actions\UploadFileAction',
//                'url' => '/statics/',
//                'path' => \Yii::getAlias("@webroot") . '/statics',
//                'uploadOnlyImage' => false,
//                'translit' => true,
//                'validatorOptions' => [
//                    'maxSize' => 40000
//                ]
//            ]
        ];
    }

    /**
     * Lists all BlockCategory models.
     * @param $parent_id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionIndex($parent_id)
    {
        $searchModel = new BlockCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $category = BlockCategory::find()->where(['id' => $parent_id])->one();
        $block = Block::findOrFail($category->block_id);

        $root = BlockCategory::find()->where(['block_id' => $block->id, 'parent_id' => 0])->one();
        $searchModel->parent_id = $root->id;
        $searchModel->block_id = $root->block_id;

        $parents = $category ? $category->getParents()->withoutRoot()->all() : null;

        ##

        $modelFieldsForm = new BlockFieldsCategoryForm($block);

        if ($this->request->isPost && $modelFieldsForm->load(Yii::$app->request->post()) && $modelFieldsForm->save()) {
            Yii::$app->session->setFlash('success', 'Поля сохранены');
            return $this->refresh();
        }

        $modelFieldsItemsForm = new BlockFieldsItemForm($block);

        if ($this->request->isPost && $modelFieldsItemsForm->load(Yii::$app->request->post()) && $modelFieldsItemsForm->save()) {
            Yii::$app->session->setFlash('success', 'Поля сохранены');
            return $this->refresh();
        }

        ##

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'block' => $block,
            'category' => $category,
            'parents' => $parents,
            'root' => $root,
            'modelFieldsForm' => $modelFieldsForm,
            'modelFieldsItemsForm' => $modelFieldsItemsForm,
        ]);
    }

    /**
     * Displays a single BlockCategory model.
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
     * Creates a new BlockCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $parent_id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionCreate($parent_id)
    {
        $this->layout = $this->module->layoutPure;

        $category = BlockCategory::findOrFail($parent_id);
        $block = Block::findOrFail($category->block_id);
        $parents = $category->getParents()->withoutRoot()->all();

        $model = new BlockCategory([
            'block_id' => $block->id,
            'parent_id' => $parent_id,
            'sort' => 100,
            'public' => 1,
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->setAttribute('create_user', Yii::$app->user->id);
            $model->setAttribute('create_date', date('Y-m-d H:i:s'));
            $model->appendTo($category)->save();
            TagDependency::invalidate(Yii::$app->cache, 'block_categories_' . $category->block_id);
            Yii::$app->session->setFlash('success', $block->translate->category . " добавлен");
            return $this->redirect(['index', 'parent_id' => $parent_id]);
        }

        return $this->render('create', [
            'block' => $block,
            'category' => $category,
            'parents' => $parents,
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BlockCategory model.
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
        $parents = $category->getParents()->withoutRoot()->all();

        $model->setAttribute('update_user', Yii::$app->user->id);
        $model->setAttribute('update_date', date('Y-m-d H:i:s'));

        if ($model->load(Yii::$app->request->post())) {
            if ($model->getOldAttribute('parent_id') !== (int) $model->getAttribute('parent_id')) {
                $newCategory = BlockCategory::findOne($model->getAttribute('parent_id'));
                $model->appendTo($newCategory);
            }
            if ($model->save()) {
                TagDependency::invalidate(Yii::$app->cache, 'block_categories_' . $category->block_id);
                Yii::$app->session->setFlash('success', $block->translate->category . " обновлен");
                return $this->redirect(['index', 'parent_id' => $parent_id]);
            }
        }

        return $this->render('update', [
            'block' => $block,
            'category' => $category,
            'parents' => $parents,
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BlockCategory model.
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
        TagDependency::invalidate(Yii::$app->cache, 'block_categories_' . $model->block_id);
        $model->deleteWithChildren();
        return $this->redirect(['block-category/index', 'parent_id' => (int) $_GET['parent_id']]);
    }

    public function actionDeletePhoto($id, $field)
    {
        $model = $this->findModel($id);
        (new Images())->removeImage($model->{$field});
        $model->updateAttributes([$field => null]);
        TagDependency::invalidate(Yii::$app->cache, 'block_categories_' . $model->block_id);
        return $this->redirect(['update', 'id' => $id, 'parent_id' => $model->parent_id]);
    }

    public function actionSortCategory($type, $item, $node)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $node = BlockCategory::findOne($node);
        $item = BlockCategory::findOne($item);

        if ($type === 'after') {
            $item->insertAfter($node)->save() or die(var_dump($item->getErrors()));
        } elseif ($type === 'before') {
            $item->insertBefore($node)->save() or die(var_dump($item->getErrors()));
        }
        TagDependency::invalidate(Yii::$app->cache, 'block_categories_' . $item->block_id);

        return [$type, $item, $node];
    }

    public function actionSortItems()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $ids = filter_var_array($_GET['ids'], FILTER_VALIDATE_INT);

        $items = BlockItem::find()
            ->where(['IN', 'id', $ids])
            ->orderBy(new Expression('FIELD(id,'.implode(',', $ids).')'))
            ->all();

        $i = 100;
        foreach ($items as $item) {
            $item->sort = $i+=10;
            $item->save();
        }

        return $items;
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionOptionsTaskMove($categoryId, array $keys = [])
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        foreach ($keys as $key) {
            $blockItem = BlockItem::findOrFail(['id' => $key]);
            $blockItem->parent_id = (int) $categoryId;
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
    public function actionOptionsTaskActivate($categoryId, array $keys = [])
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        foreach ($keys as $key) {
            $blockItem = BlockItem::findOrFail(['id' => $key]);
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
    public function actionOptionsTaskDeactivate($categoryId, array $keys = [])
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        foreach ($keys as $key) {
            $blockItem = BlockItem::findOrFail(['id' => $key]);
            $blockItem->public = 0;
            $blockItem->save() or die(var_dump($blockItem->getErrors()));
        }

        TagDependency::invalidate(Yii::$app->cache, 'block_items_' . $blockItem->block_id);

        return [
            'result' => 'success'
        ];
    }

    /**
     * Finds the BlockCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BlockCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BlockCategory::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
