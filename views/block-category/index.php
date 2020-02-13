<?php

use thefx\blocks\forms\BlockFieldsCategoryForm;
use thefx\blocks\forms\search\BlockCategorySearch;
use thefx\blocks\models\blocks\Block;
use thefx\blocks\models\blocks\BlockCategory;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel BlockCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $block Block */
/* @var $category BlockCategory */
/* @var $parents BlockCategory[] */
/* @var $modelFieldsForm BlockFieldsCategoryForm */

$this->title = $block->translate->categories;
if ($category && !$category->isRoot()) {
    $this->title = $category->title;
}
if ($parents) {
    $this->params['breadcrumbs'][] = ['label' => $block->translate->categories, 'url' => ['index', 'parent_id' => $parents[0]->parent_id]];
    foreach ($parents as $parent) {
        $this->params['breadcrumbs'][] = ['label' => $parent->title, 'url' => ['block-category/index', 'parent_id' => $parent->id]];
    }
} else if (!$category->isRoot()) {
    $this->params['breadcrumbs'][] = ['label' => $block->translate->categories, 'url' => ['index', 'parent_id' => $category->parent_id]];
}
$this->params['breadcrumbs'][] = $this->title;
$this->params['title_btn'] = (Yii::$app->user->id == 1) ? $this->render('_modal', ['modelFieldsForm' => $modelFieldsForm]) : null; ?>

<div class="block-category-index">

<!--    --><?php //Pjax::begin(); ?>

    <?= Html::a($block->translate->block_create, ['block-item/create', 'parent_id' => $category->id], ['class' => 'btn btn-success']) ?>
    <?= Html::a($block->translate->category_create, ['create', 'parent_id' => $category->id], ['class' => 'btn btn-default']) ?>

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?php
        $columns = [];
        foreach ($block->getFieldsCategoryTemplates() as $item) {
            switch ($item['value']) {
                case 'title':
                    $columns[] = [
                        'attribute' => 'title',
    //                'label' => '',
                        'format' => 'html',
                        'value' => static function(BlockCategory  $model) use ($category) {
                            if ($model->isFolder()) {
                                return '<i class="fa fa-folder text-muted position-left"></i> ' . Html::a($model->title, ['index', 'parent_id' => $model->id]);
                            }
                            return Html::a($model->title, ['block-item/update', 'id' => $model->id, 'parent_id' => $category->id]);
                        },
                    ];
                    break;
                case 'date':
                    $columns[] = 'date';
                    break;
                case 'anons':
                    $columns[] = 'anons';
                    break;
                case 'text':
                    $columns[] = 'text';
                    break;
                case 'public':
                    $columns[] = [
                        'attribute' => 'public',
                        'headerOptions' => ['style' => 'width:85px; text-align:center'],
                        'contentOptions' => ['style' => 'text-align:center'],
                        'content' => static function(BlockCategory $row) {
                            return $row->public ? '<span class="label label-success">Да</span>' : '<span class="label label-default">Нет</span>';
                        }
                    ];
                    break;
                case 'id':
                    $columns[] = [
                        'attribute' => 'id',
                        'headerOptions' => ['style' => 'width:85px; text-align:center'],
                        'contentOptions' => ['style' => 'text-align:center'],
                    ];
                    break;
            }
        }
        $columns[] = [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update}{delete}',
            'urlCreator' => static function($action, BlockCategory $model, $key, $index) use ($category) {
                $params = is_array($key) ? $key : ['id' => (string)$key];
                $params['parent_id'] = $category->id;
                $params[0] = ($model->isFolder() ? 'block-category' : 'block-item') . '/' . $action;
                return Url::toRoute(array_filter($params));
            },
        ];
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => $columns,
    ]) ?>
<!--    --><?php //Pjax::end(); ?>

</div>
