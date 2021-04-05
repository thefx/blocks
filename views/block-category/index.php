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
$this->params['title_btn'] = (Yii::$app->user->id == 1) ? $this->render('_modal', ['modelFieldsForm' => $modelFieldsForm]) : null;

\thefx\blocks\assets\SortableJs\SortableJsAsset::register($this);

?>

<script>

    document.addEventListener("DOMContentLoaded", function () {

        function sortFolders(data) {
            if (data.prev.length !== 0) {
                var dataJson = {'type': 'after', 'item' : data.id, 'node' : data.prev.data('key')};
                $.get("<?=Url::to(['sort-category'])?>", dataJson);
            } else if (data.next.length !== 0) {
                var dataJson = {'type': 'before', 'item' : data.id, 'node' : data.next.data('key')};
                $.get("<?=Url::to(['sort-category'])?>", dataJson);
            }
        }

        // Sortable
        document.querySelectorAll('table tbody').forEach(function (el) {
            Sortable.create(el, {
                draggable: 'tr[data-type="folder"]',
                handle: ".handle",
                onEnd: function (/**Event*/evt) {
                    var data = {
                        'id'       : $(evt.item).data('key'),
                        'next'     : $(evt.item).next('tr[data-type="folder"]'),
                        'prev'     : $(evt.item).prev('tr[data-type="folder"]'),
                    };
                    sortFolders(data);
                    // var itemEl = evt.item;  // dragged HTMLElement
                    // evt.to;    // target list
                    // evt.from;  // previous list
                    // evt.oldIndex;  // element's old index within old parent
                    // evt.newIndex;  // element's new index within new parent
                    // evt.oldDraggableIndex; // element's old index within old parent, only counting draggable elements
                    // evt.newDraggableIndex; // element's new index within new parent, only counting draggable elements
                    // evt.clone // the clone element
                    // evt.pullMode;  // when item is in another sortable: `"clone"` if cloning, `true` if moving
                },
            });
        });

        // Sortable
        //document.querySelectorAll('table tbody').forEach(function (el) {
        //    Sortable.create(el, {
        //        draggable: 'tr[data-type="item"]',
        //        handle: ".handle",
        //        onEnd: function (/**Event*/evt) {
        //            var ids = [];
        //            $(evt.target).find('tr[data-type="item"]').each(function (key, element) {
        //                ids.push(element.getAttribute('data-key'));
        //            });
        //            $.get("<?//=Url::to(['sort-items'])?>//", {'ids': ids});
        //        },
        //    });
        //});

    });

</script>

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

<div class="block-category-index">

    <?php
        $columns = [];
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
                        'value' => static function(BlockCategory  $model) use ($category, $item) {
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
                        'value' => static function(BlockCategory  $model) use ($category) {
                            if ($model->isFolder()) {
                                return '<i class="fa fa-folder text-muted position-left"></i> ' . Html::a($model->title, ['index', 'parent_id' => $model->id]);
                            }
                            return Html::a($model->title, ['block-item/update', 'id' => $model->id, 'parent_id' => $category->id], ['data-pjax' => '0']);
                        },
                    ];
                    break;
                case 'sort':
                    $columns[] = [
                        'label' => 'Сорт.',
                        'attribute' => 'lft',
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
                        'content' => static function(BlockCategory $row) {
                            return strip_tags($row->anons);
                        }
                    ];
                    break;
                case 'text':
                    $columns[] = [
                        'attribute' => 'text',
                        'content' => static function(BlockCategory $row) {
                            return strip_tags($row->text);
                        }
                    ];
                    break;
                case 'public':
                    $columns[] = [
                        'attribute' => 'public',
                        'headerOptions' => ['style' => 'width:85px; text-align:center'],
                        'contentOptions' => ['style' => 'text-align:center'],
                        'content' => static function(BlockCategory $row) {
                            return $row->public ? '<span class="badge badge-success">Да</span>' : '<span class="badge">Нет</span>';
                        }
                    ];
                    break;
                case 'update_date':
                    $columns[] = [
                        'attribute' => 'update_date',
                        'headerOptions' => ['style' => 'width:200px; text-align:center'],
                        'contentOptions' => ['style' => 'text-align:center'],
                        'content' => static function(BlockCategory $row) {
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
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update}{delete}',
            'headerOptions' => ['style' => 'width:40px; text-align:center'],
            'contentOptions' => ['style' => 'text-align:center'],
            'urlCreator' => static function($action, BlockCategory $model, $key, $index) use ($category) {
                $params = is_array($key) ? $key : ['id' => (string)$key];
                $params['parent_id'] = $category->id;
                $params[0] = ($model->isFolder() ? 'block-category' : 'block-item') . '/' . $action;
                return Url::toRoute(array_filter($params));
            },
        ];
    ?>

<!--    --><?php //\yii\widgets\Pjax::begin(); ?>

    <?= Html::a($block->translate->block_create, ['block-item/create', 'parent_id' => $category->id], ['class' => 'btn btn-success']) ?>
    <?= Html::a($block->translate->category_create, ['create', 'parent_id' => $category->id], ['class' => 'btn btn-default']) ?>

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => $columns,
        'rowOptions' => function ($model, $key, $index, $grid) {
            return ['data-type' => $model->type];
        },
    ]) ?>

<!--    --><?php //\yii\widgets\Pjax::end(); ?>

</div>
