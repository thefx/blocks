<?php

use thefx\blocks\forms\search\BlockCategorySearch;
use thefx\blocks\models\blocks\BlockFields;
use thefx\blocks\models\blocks\BlockItem;
use yii\grid\CheckboxColumn;
use yii\grid\ActionColumn;
use thefx\blocks\models\blocks\Block;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel BlockCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $block Block */
/* @var $category BlockCategorySearch */
/* @var $parents BlockCategorySearch[] */
/* @var $series BlockItem|null */

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
} else if ($series && $category->isRoot()) {
    $this->params['breadcrumbs'][] = ['label' => $block->translate->categories, 'url' => ['block-category/index', 'parent_id' => $series->parent_id]];
}
if ($series) {
    $this->title = 'Серия ' . $series->title;
    $this->params['breadcrumbs'][] = ['label' => $category->title, 'url' => ['block-category/index', 'parent_id' => $category->id]];
}

$this->params['breadcrumbs'][] = $this->title;

\thefx\blocks\assets\SortableJs\SortableJsAsset::register($this);

$settings = array_merge(Yii::$app->params['block'], Yii::$app->params['block' . $block->id] ?? []);
?>

<style>
    .table > thead > tr > th,
    .table > tbody > tr > th,
    .table > tfoot > tr > th,
    .table > thead > tr > td,
    .table > tbody > tr > td,
    .table > tfoot > tr > td {
        vertical-align:middle
    }
    .handle {
        cursor: move;
    }
</style>

<?php if (in_array(Yii::$app->user->id, $this->context->module->rootUsers, true)) :?>
    <div class="mb-3">
        <?= Html::a('Элемент', ['block-fields/update', 'block_id' => $block->id, 'type' => BlockFields::BLOCK_TYPE_ITEM], ['class' => 'btn btn-default btn-sm']) ?>
        <?= Html::a('Серия', ['block-fields/update', 'block_id' => $block->id, 'type' => BlockFields::BLOCK_TYPE_SERIES], ['class' => 'btn btn-default btn-sm']) ?>
        <?= Html::a('Категории', ['block-fields/update', 'block_id' => $block->id, 'type' => BlockFields::BLOCK_TYPE_CATEGORY], ['class' => 'btn btn-default btn-sm']) ?>
        <hr>
    </div>
<?php endif; ?>

<div class="block-category-index">

    <?php
        $columns = [];
        $columns[] = [
            'class' => CheckboxColumn::class,
            'headerOptions' => ['style' => 'width:40px; text-align:center'],
            'contentOptions' => ['style' => 'text-align:center'],
            'content' => static function(BlockCategorySearch $row) {
                return $row->type === BlockCategorySearch::TYPE_ITEM ? Html::checkbox('selection[]', false, ['value' => $row->id]) : null;
            }
        ];
//        $columns[] = [
//            'label' => '',
//            'headerOptions' => ['style' => 'width:40px; text-align:center'],
//            'contentOptions' => ['style' => 'width:40px; text-align:center', 'class' => 'handle'],
//            'format' => 'html',
//            'value' => static function() {
//                return '<i class="fa fa-arrows-alt text-muted" aria-hidden="true"></i>';
//            },
//        ];
        foreach ($block->getFieldsCategoryTemplates() as $item) {
            switch ($item['value']) {
                case 'photo_preview':
                case 'photo':
                    $columns[] = [
                        'attribute' => 'photo_preview',
                        'headerOptions' => ['style' => 'width:140px; text-align:center'],
                        'contentOptions' => ['style' => 'width:140px; text-align:center'],
                        'format' => 'html',
                        'value' => static function(BlockCategorySearch  $model) use ($category, $item) {
                            $img = Html::img($model->getPhoto($item['value']), ['style' => 'max-width:100px; max-height:100px']);
                            $url = $model->isFolder() ? ['index', 'parent_id' => $model->id] : ['block-item/update', 'id' => $model->id, 'parent_id' => $category->id];
                            return Html::a($img, $url, ['data-pjax' => '0']);
                        },
                    ];
                    break;
                case 'title':
                    $columns[] = [
                        'attribute' => 'title',
    //                'label' => '',
                        'format' => 'html',
                        'value' => static function(BlockCategorySearch $model) use ($category) {

                            if ($model->isFolder()) {
                                $icon = '<i class="fa fa-folder text-muted position-left"></i>';
                                $url =  ['block-category/index', 'parent_id' => $model->id];
                                return Html::a($icon . '&nbsp;' . $model->title, $url);
                            }

                            if ($model->isSeries()) {
                                $icon = '<i class="fas fa-layer-group text-muted position-left"></i> ';
                                $url =  ['block-category/index', 'series_id' => $model->id, 'parent_id' => $model->parent_id];
                                return $icon . Html::a($model->title, $url, ['data-pjax' => '0']);
                            }

                            $url =  ['block-item/update', 'id' => $model->id, 'parent_id' => $category->id];
                            return Html::a($model->title, $url, ['data-pjax' => '0']);
                        },
                    ];
                    break;
                case 'sort':
                    $columns[] = [
                        'label' => 'Сорт.',
                        'attribute' => 'sort',
                        'headerOptions' => ['style' => 'width:85px; text-align:center'],
                        'contentOptions' => ['style' => 'text-align:center'],
                    ];
                    break;
                case 'date':
                    $columns[] = 'date';
                    break;
                case 'anons':
                    $columns[] = [
                        'attribute' => 'anons',
                        'content' => static function(BlockCategorySearch $row) {
                            return strip_tags($row->anons);
                        }
                    ];
                    break;
                case 'text':
                    $columns[] = [
                        'attribute' => 'text',
                        'content' => static function(BlockCategorySearch $row) {
                            return strip_tags($row->text);
                        }
                    ];
                    break;
                case 'public':
                    $columns[] = [
                        'attribute' => 'public',
                        'headerOptions' => ['style' => 'width:85px; text-align:center'],
                        'contentOptions' => ['style' => 'text-align:center'],
                        'content' => static function(BlockCategorySearch $row) {
                            return $row->public ? '<span class="badge badge-success">Да</span>' : '<span class="badge">Нет</span>';
                        }
                    ];
                    break;
                case 'update_date':
                    $columns[] = [
                        'attribute' => 'update_date',
                        'headerOptions' => ['style' => 'width:200px; text-align:center'],
                        'contentOptions' => ['style' => 'text-align:center'],
                        'content' => static function(BlockCategorySearch $row) {
                            return $row->update_date ? date('d.m.Y H:i:s', strtotime($row->update_date)) : date('d.m.Y H:i:s', strtotime($row->create_date));
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
            'class' => ActionColumn::class,
            'template' => '{update}{copy}{delete}',
            'headerOptions' => ['style' => 'width:40px; text-align:center'],
            'contentOptions' => ['style' => 'text-align:center'],
            'urlCreator' => static function($action, BlockCategorySearch $model, $key, $index) use ($category) {
                $params = is_array($key) ? $key : ['id' => (string)$key, 'parent_id' => $category->id];

                if ($model->isFolder()) {
                    $params[0] = 'block-category' . '/' . $action;
                } elseif ($model->isSeries()) {
                    $params[0] = 'block-item' . '/' . $action;
                } else {
                    $params[0] = 'block-item' . '/' . $action;
                }
                return Url::toRoute(array_filter($params));
            },
            'visibleButtons' => [
                'copy' => static function (BlockCategorySearch $model) {
                    return $model->isItem();
                },
            ],
            'buttons' => [
                'copy' => static function($url, $model) {
                    return Html::a('<span class="fa fa-copy position-left mr-2"></span>Копировать', $url, ['title' => 'Копировать', 'class'=> 'dropdown-item', 'data-pjax' => '0']);
                }
            ]
        ];
    ?>

<!--    --><?php //\yii\widgets\Pjax::begin(); ?>

    <?= Html::a($block->translate->block_create, ['block-item/create', 'parent_id' => $category->id, 'series_id' => $series->id ?? null], ['class' => 'btn btn-success']) ?>

    <?php if (!$series && $settings['btn_add_series']) : ?>
        <?= Html::a('Добавить серию', ['block-item/create', 'parent_id' => $category->id, 'type' => BlockItem::TYPE_SERIES], ['class' => 'btn btn-success']) ?>
    <?php endif; ?>

    <?php if (!$series && $settings['btn_add_group']) : ?>
        <?= Html::a($block->translate->category_create, ['create', 'parent_id' => $category->id], ['class' => 'btn btn-default']) ?>
    <?php endif; ?>

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'options' => ['id' => 'tableTree'],
        'columns' => $columns,
        'layout' => $this->render('_tableTreeOptions', ['block' => $block]),
        'rowOptions' => static function ($model, $key, $index, $grid) {
            return ['data-type' => $model->type];
        },
    ]) ?>

<!--    --><?php //\yii\widgets\Pjax::end(); ?>

</div>
