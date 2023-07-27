<?php

namespace thefx\blocks\controllers;

use thefx\blocks\models\Block;
use thefx\blocks\models\BlockItem;
use thefx\blocks\models\BlockSections;
use thefx\blocks\models\forms\search\BlockSectionsSearch;
use Yii;
use yii\caching\TagDependency;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * BlockSectionsController implements the CRUD actions for BlockSections model.
 */
class BlockSectionsController extends Controller
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
//                'validatorOptions' => [
//                    'maxWidth' => 2000,
//                    'maxHeight' => 2000
//                ]
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
     * Lists all BlockSections models.
     * @param int $section_id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionIndex($block_id, $section_id = 0)
    {
        $searchModel = new BlockSectionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($section_id) {
            $section = BlockSections::findOrFail(['id' => $section_id]);
            $parents = $section->getParents()->all();
        }

        $block = Block::findOrFail($block_id);

        $searchModel->section_id = $section_id;
        $searchModel->block_id = $block_id;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'block' => $block,
            'section' => $section ?? null,
            'parents' => $parents ?? null,
        ]);
    }

    /**
     * Displays a single BlockSections model.
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
     * Creates a new BlockSections model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $section_id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionCreate(int $block_id, int $section_id = 0)
    {
        $this->layout = $this->module->layoutPure;

        $block = Block::findOrFail($block_id);

        if ($section_id) {
            $section = BlockSections::findOrFail(['id' => $section_id]);
            $parents = $section->getParents()->all();
        }

        $model = BlockSections::create($block->id, $section_id);

        if ($this->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->section_id) {
                $parentNode = BlockSections::findOne(['id' => $model->section_id]);
                $model->appendTo($parentNode);
            } else if (($lastNode = BlockSections::getLastNode($model->block_id, $model->id)) !== null){
                $model->insertAfter($lastNode);
            } else {
                $model->makeRoot();
            }
            if ($model->save()) {
                TagDependency::invalidate(Yii::$app->cache, 'block_categories_' . $block_id);
                Yii::$app->session->setFlash('success', $block->translate->category . " добавлен");
                return $this->redirect(['index', 'block_id' => $model->block_id, 'section_id' => $model->section_id]);
            }
        }

        return $this->render('create', [
            'block' => $block,
            'section' => $section ?? null,
            'parents' => $parents ?? null,
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BlockSections model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $this->layout = $this->module->layoutPure;

        $model = $this->findModel($id);

        if ($model->section_id) {
            $category = BlockSections::findOrFail($model->section_id);
            $parents = $category->getParents()->all();
        }

        if ($this->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->section_id) {
                $parentNode = BlockSections::findOne(['id' => $model->section_id]);
                $model->appendTo($parentNode);
            } else if (($lastNode = BlockSections::getLastNode($model->block_id, $model->id)) !== null){
                $model->insertAfter($lastNode);
            } else {
                $model->makeRoot();
            }
            $model->setAttribute('update_user', Yii::$app->user->id);
            $model->setAttribute('update_date', date('Y-m-d H:i:s'));
            if ($model->save()) {
                TagDependency::invalidate(Yii::$app->cache, 'block_categories_' . $model->block_id);
                Yii::$app->session->setFlash('success', $model->block->translate->category . " обновлен");
                return $this->redirect(['index', 'block_id' => $model->block_id, 'section_id' => $model->section_id]);
            }
        }

        return $this->render('update', [
            'block' => $model->block,
            'category' => $category ?? null,
            'parents' => $parents ?? null,
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BlockSections model.
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
        TagDependency::invalidate(Yii::$app->cache, 'block_categories_' . $model->block_id);
        $model->deleteWithChildren();
        return $this->redirect(['index', 'block_id' => $block_id]);
    }

    public function actionDeletePhoto($id, $field)
    {
        $model = BlockSections::findOrFail($id);
//        (new Images())->removeImage($model->{$field});
        $model->updateAttributes([$field => null]);
        TagDependency::invalidate(Yii::$app->cache, 'block_categories_' . $model->block_id);
        return $this->redirect(['update', 'id' => $id]);
    }

//    public function actionSortCategory($type, $item, $node)
//    {
//        Yii::$app->response->format = Response::FORMAT_JSON;
//
//        $node = BlockSections::findOne($node);
//        $item = BlockSections::findOne($item);
//
//        if ($type === 'after') {
//            $item->insertAfter($node)->save() or die(var_dump($item->getErrors()));
//        } elseif ($type === 'before') {
//            $item->insertBefore($node)->save() or die(var_dump($item->getErrors()));
//        }
//        TagDependency::invalidate(Yii::$app->cache, 'block_categories_' . $item->block_id);
//
//        return [$type, $item, $node];
//    }
//
//    public function actionSortItems()
//    {
//        Yii::$app->response->format = Response::FORMAT_JSON;
//
//        $ids = filter_var_array($_GET['ids'], FILTER_VALIDATE_INT);
//
//        $items = BlockItem::find()
//            ->where(['IN', 'id', $ids])
//            ->orderBy(new Expression('FIELD(id,'.implode(',', $ids).')'))
//            ->all();
//
//        $i = 100;
//        foreach ($items as $item) {
//            $item->sort = $i+=10;
//            $item->save();
//        }
//
//        return $items;
//    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionOptionsTaskMove(int $sectionId, array $keys = [])
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        foreach ($keys as $key) {
            $blockItem = BlockItem::findOrFail(['id' => $key]);
            $blockItem->section_id = (int) $sectionId;
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
    public function actionOptionsTaskActivate(int $sectionId, array $keys = [])
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        foreach ($keys as $key) {
            $model = BlockItem::findOrFail(['id' => $key]);
            $model->public = 1;
            $model->save(false) or die(var_dump($model->getErrors()));
        }

        TagDependency::invalidate(Yii::$app->cache, 'block_items_' . $model->block_id);

        return [
            'result' => 'success'
        ];
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionOptionsTaskDeactivate(int $sectionId, array $keys = [])
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        foreach ($keys as $key) {
            $model = BlockItem::findOrFail(['id' => $key]);
            $model->public = 0;
            $model->save(false) or die(var_dump($model->getErrors()));
        }

        TagDependency::invalidate(Yii::$app->cache, 'block_items_' . $model->block_id);

        return [
            'result' => 'success'
        ];
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionOptionsTaskDelete(int $sectionId, array $keys = [])
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        foreach ($keys as $key) {
            $model = BlockItem::findOrFail(['id' => $key]);
            $model->delete();
        }

        TagDependency::invalidate(Yii::$app->cache, 'block_items_' . $model->block_id);

        return [
            'result' => 'success'
        ];
    }

    /**
     * Finds the BlockSections model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BlockSections the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BlockSections::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
