<?php

namespace thefx\blocks\controllers;

use thefx\blocks\models\Block;
use thefx\blocks\models\BlockFields;
use thefx\blocks\models\BlockItem;
use thefx\blocks\models\BlockItemPropertyAssignments;
use thefx\blocks\models\BlockProperty;
use thefx\blocks\models\BlockPropertyElement;
use thefx\blocks\models\BlockSections;
use thefx\blocks\models\BlockTranslate;
use thefx\blocks\models\forms\search\BlockPropertySearch;
use thefx\blocks\models\forms\search\BlockSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * BlockController implements the CRUD actions for Block model.
 */
class BlockController extends Controller
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
     * Lists all Block models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BlockSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Block model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = Block::create();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            $translate = BlockTranslate::create($model->id);
            $translate->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Block model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Block model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $this->layout = $this->module->layoutPure;
        $block = $this->findModel($id);

        $propsSearchModel = new BlockPropertySearch();
        $propsDataProvider = $propsSearchModel->search(Yii::$app->request->queryParams);
        $propsDataProvider->query->andWhere(['block_id' => $block->id]);

        return $this->render('view', [
            'model' => $block,
            'propsSearchModel' => $propsSearchModel,
            'propsDataProvider' => $propsDataProvider,
        ]);
    }

    /**
     * Deletes an existing Block model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $blockPropsIds = BlockProperty::find()->select('id')->where(['block_id' => $id])->column();

        BlockItemPropertyAssignments::deleteAll(['property_id' => $blockPropsIds]);
        BlockPropertyElement::deleteAll(['property_id' => $blockPropsIds]);

        BlockProperty::deleteAll(['block_id' => $id]);
        BlockItem::deleteAll(['block_id' => $id]);
        BlockSections::deleteAll(['block_id' => $id]);
        BlockFields::deleteAll(['block_id' => $id]);
        BlockTranslate::deleteAll(['block_id' => $id]);
        Block::deleteAll(['id' => $id]);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Block model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Block the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Block::find()->where(['id' => $id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
