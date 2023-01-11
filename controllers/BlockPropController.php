<?php

namespace thefx\blocks\controllers;

use thefx\blocks\models\blocks\BlockFields;
use thefx\blocks\services\TransactionManager;
use thefx\blocks\forms\search\BlockPropSearch;
use thefx\blocks\models\blocks\BlockProp;
use thefx\blocks\models\blocks\BlockPropElem;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Module;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;

/**
 * BlockPropController implements the CRUD actions for BlockProp model.
 */
class BlockPropController extends Controller
{
    public $transaction;

    public function __construct($id, Module $module, TransactionManager $transaction, array $config = [])
    {
        $this->transaction = $transaction;
        parent::__construct($id, $module, $config);
    }

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
     * Lists all BlockProp models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BlockPropSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BlockProp model.
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
     * Creates a new BlockProp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $block_id
     * @return mixed
     * @throws InvalidConfigException
     */
    public function actionCreate($block_id)
    {
        $this->layout = $this->module->layoutPure;

        $model = new BlockProp([
            'sort' => 100,
            'block_id' => $block_id,
            'in_filter' => 0,
            'required' => 0,
            'multi' => 0,
            'public' => 1,
            'redactor' => 0,
        ]);

        if ($model->load(Yii::$app->request->post())) {
            $model->loadRelations(Yii::$app->request->post());
            if ($model->save()) {
                return $this->redirect(['block/view', 'id' => $model->block_id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BlockProp model.
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
            $oldKeys = array_column($model->elements, 'id');
            $model->loadRelations(Yii::$app->request->post());

            $newKeys = array_column($model->elements, 'id');
            $diffKeys = array_diff($oldKeys, $newKeys);
            if (!empty($diffKeys)) {
                $elements = array_filter($model->elements, function ($element) use ($diffKeys) {
                    return !in_array($element->id, $diffKeys);
                });
                $model->elements = $elements;
            }

            // Nothing come
            if (!array_key_exists('BlockPropElem', Yii::$app->request->post())) {
                $model->elements = [];
            }

            $sort = 1;
            foreach ($model->elements as $element) {
                $element->sort = $sort++;
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

        $elem = new BlockPropElem([
            'sort' => 100,
        ]);

        return $this->renderAjax('_form_elem', [
            'index' => $index,
            'form' => $form,
            'model' => $elem,
        ]);
    }

    /**
     * Deletes an existing BlockProp model.
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
     * Finds the BlockProp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BlockProp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BlockProp::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
