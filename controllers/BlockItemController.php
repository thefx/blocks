<?php

namespace thefx\blocks\controllers;

use thefx\blocks\models\Block;
use thefx\blocks\models\BlockItem;
use thefx\blocks\models\forms\BlockItemForm;
use thefx\blocks\models\forms\search\BlockItemSearch;
use thefx\blocks\widgets\DropzoneWidget\actions\DropzoneUploadAction;
use vova07\imperavi\actions\GetImagesAction;
use vova07\imperavi\actions\UploadFileAction;
use Yii;
use yii\caching\TagDependency;
use yii\db\StaleObjectException;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
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
                'class' => UploadFileAction::class,
                'url' => '/upload/redactor/block-item/' . $id . '/',
                'path' => \Yii::getAlias("@webroot") . '/upload/redactor/block-item/' . $id,
                'unique' => true,
//                'validatorOptions' => [
//                    'maxWidth' => 2000,
//                    'maxHeight' => 2000
//                ]
            ],
            'get-uploaded-images' => [
                'class' => GetImagesAction::class,
                'url' => '/upload/redactor/block-item/' . $id . '/',
                'path' => \Yii::getAlias("@webroot") . '/upload/redactor/block-item/' . $id,
                'options' => ['only' => ['*.jpg', '*.jpeg', '*.png', '*.gif', '*.ico']],
            ],
            'add-file' => [
                'class' => DropzoneUploadAction::class,
            ],
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
     * @param $section_id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionCreate($block_id, $section_id = 0)
    {
        $this->layout = $this->module->layoutPure;

        $model = BlockItemForm::create($block_id, $section_id);

        // for fields
        $block = Block::find()
            ->where(['id' => $model->block_id])
            ->with(['fields.property', 'fields.children.property'])
            ->one();

        if ($this->request->isPost && $model->load(Yii::$app->request->post()) && $model->loadPropertyAssignments(Yii::$app->request->post()) && $model->save()) {
            TagDependency::invalidate(Yii::$app->cache, 'block_items_' . $block->id);
            Yii::$app->session->setFlash('success', $block->translate->block_item . ' добавлен');
            return $this->redirect(['block-sections/index', 'block_id' => $block->id, 'section_id' => $section_id]);
        }

        return $this->render('create', [
            'block' => $block,
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BlockItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $this->layout = $this->module->layoutPure;

        $model = BlockItemForm::findForUpdate($id);

        // for fields
        $block = Block::find()
            ->where(['id' => $model->block_id])
//            ->with(['fields.property', 'fields.children.property'])
            ->with(['fields', 'fields.children'])
            ->one();

        if ($this->request->isPost &&
            $model->load(Yii::$app->request->post()) &&
            $model->loadPropertyAssignments(Yii::$app->request->post()) &&
            /*Model::validateMultiple($model->propertyAssignmentsUpdate) &&*/ $model->save()) {

            TagDependency::invalidate(Yii::$app->cache, 'block_items_' . $model->block_id);
            Yii::$app->session->setFlash('success', $model->block->translate->block_item . ' обновлен');
            return $this->redirect(['block-sections/index', 'block_id' => $model->block_id]);
        }

        return $this->render('update', [
            'block' => $block,
            'model' => $model,
        ]);
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
        $model = BlockItem::findOrFail($id);
        $block = Block::findOrFail($model->block_id);
        $sectionId = $model->section_id;
        $blockId = $model->block_id;
        $elementName = $block->translate->block_item;
        TagDependency::invalidate(Yii::$app->cache, 'block_items_' . $model->block_id);
        $model->delete();
        Yii::$app->session->setFlash('success', $elementName . ' удален');
        return $this->redirect(['block-sections/index', 'block_id' => $model->block_id]);
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
                'propertyAssignments.property',
                'propertyAssignments.property.elements'
            ])->where(['id' => $id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDeletePhoto($id, $field)
    {
        $model = BlockItem::findOrFail($id);
//        (new Images())->removeImage($model->{$field});
        $model->updateAttributes([$field => null]);
        TagDependency::invalidate(Yii::$app->cache, 'block_items_' . $model->block_id);
        return $this->redirect(['update', 'id' => $id, 'parent_id' => $model->section_id]);
    }

//    public function actionGetFileInfo($filename)
//    {
//        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//
//        $model = Files::findOne(['file' => $filename]);
//
//        return [
//            'result' => 'success',
//            'model' => $model
//        ];
//    }
//
//    public function actionEditFileInfo()
//    {
//        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//
//        $model = Files::findOne(['file' => $_POST['Files']['file']]);
//
//        if ($model) {
//            $model->load(Yii::$app->request->post());
//            $model->save();
//        }
//
//        return [
//            'result' => 'success',
//            'model' => $model,
//        ];
//    }
}
