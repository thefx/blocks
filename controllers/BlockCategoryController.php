<?php

namespace thefx\blocks\controllers;

use thefx\blocks\forms\BlockFieldsCategoryForm;
use thefx\blocks\forms\search\BlockCategorySearch;
use thefx\blocks\models\blocks\Block;
use thefx\blocks\models\blocks\BlockCategory;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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

    /**
     * Lists all BlockCategory models.
     * @param $parent_id
     * @return mixed
     */
    public function actionIndex($parent_id)
    {
        $searchModel = new BlockCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $category = BlockCategory::find()->where(['id' => $parent_id])->one();
        $block = Block::findOne($category->block_id);

        $root = BlockCategory::find()->where(['block_id' => $block->id, 'parent_id' => 0])->one();
        $searchModel->parent_id = $root->id;
        $searchModel->block_id = $root->block_id;

        $parents = $category ? $category->getParents()->withOutRoot()->all() : null;

        $modelFieldsForm = new BlockFieldsCategoryForm($block);

        if ($modelFieldsForm->load(Yii::$app->request->post()) && $modelFieldsForm->validate() && $modelFieldsForm->save()) {
            Yii::$app->session->setFlash('success', 'Поля сохранены');
            return $this->refresh();
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'block' => $block,
            'category' => $category,
            'parents' => $parents,
            'root' => $root,
            'modelFieldsForm' => $modelFieldsForm,
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
     */
    public function actionCreate($parent_id)
    {
        $this->layout = 'pure';

        $category = BlockCategory::findOne($parent_id);
        $block = Block::findOne($category->block_id);
        $parents = $category ? $category->getParents()->withOutRoot()->all() : null;

        $model = new BlockCategory([
            'block_id' => $block->id,
            'parent_id' => $parent_id,
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->setAttribute('create_user', Yii::$app->user->id);
            $model->setAttribute('create_date', date('Y-m-d H:i:s'));
            $model->appendTo($category)->save();
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
        $this->layout = 'pure';

        $model = $this->findModel($id);

        $category = BlockCategory::findOne($model->parent_id);
        $block = Block::findOne($category->block_id);
        $parents = $category ? $category->getParents()->withOutRoot()->all() : null;

        $model->setAttribute('update_user', Yii::$app->user->id);
        $model->setAttribute('update_date', date('Y-m-d H:i:s'));

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', $block->translate->category . " обновлен");
            return $this->redirect(['index', 'parent_id' => $parent_id]);
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
        $this->findModel($id)->delete();
        return $this->redirect(['block-category/index', 'parent_id' => (int) $_GET['parent_id']]);
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
