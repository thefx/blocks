<?php

namespace thefx\blocks\controllers;

use thefx\blocks\forms\search\BlockPropSearch;
use thefx\blocks\forms\search\BlockSearch;
use thefx\blocks\models\blocks\Block;
use thefx\blocks\models\blocks\BlockCategory;
use thefx\blocks\models\blocks\BlockFields;
use thefx\blocks\models\blocks\BlockItem;
use thefx\blocks\models\blocks\BlockItemPropAssignments;
use thefx\blocks\models\blocks\BlockProp;
use thefx\blocks\models\blocks\BlockPropElem;
use thefx\blocks\models\blocks\BlockSeo;
use thefx\blocks\models\blocks\BlockSettings;
use thefx\blocks\models\blocks\BlockTranslate;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
     * Displays a single Block model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $this->layout = $this->module->layoutPure;
        $block = $this->findModel($id);

        $propsSearchModel = new BlockPropSearch();
        $propsDataProvider = $propsSearchModel->search(Yii::$app->request->queryParams);
        $propsDataProvider->query->andWhere(['block_id' => $block->id]);

        return $this->render('view', [
            'model' => $block,
            'propsSearchModel' => $propsSearchModel,
            'propsDataProvider' => $propsDataProvider,
        ]);
    }

    /**
     * Creates a new Block model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Block();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $newModel = Block::create($model->title, $model->path);
            $newModel->save() or die(var_dump($newModel->getErrors()));
            return $this->redirect(['view', 'id' => $newModel->id]);
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

    public function actionSortElements(array $ids, $blockId)
    {
        $elements = BlockProp::find()
            ->where(['id' => $ids, 'block_id' => $blockId])
            ->indexBy('id')
            ->all();

        $sort = 1;

        foreach ($ids as $id) {
            $elements[$id]->sort = $sort++;
            $elements[$id]->save();
        }
        echo 'done';
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
        $blockPropsIds = BlockProp::find()->select('id')->where(['block_id' => $id])->column();

        BlockItemPropAssignments::deleteAll(['prop_id' => $blockPropsIds]);
        BlockPropElem::deleteAll(['block_prop_id' => $blockPropsIds]);

        BlockProp::deleteAll(['block_id' => $id]);
        BlockItem::deleteAll(['block_id' => $id]);
        BlockCategory::deleteAll(['block_id' => $id]);
        BlockFields::deleteAll(['block_id' => $id]);
        BlockPropSearch::deleteAll(['block_id' => $id]);
        BlockSettings::deleteAll(['block_id' => $id]);
        BlockTranslate::deleteAll(['block_id' => $id]);
        BlockSeo::deleteAll(['block_id' => $id]);
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
