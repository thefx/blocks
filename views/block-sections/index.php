<?php

use thefx\blocks\models\Block;
use thefx\blocks\models\BlockFields;
use thefx\blocks\models\BlockSections;
use thefx\blocks\models\forms\search\BlockSectionsSearch;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel BlockSectionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $block Block */
/* @var $section BlockSections */
/* @var $parents BlockSections[] */

$this->title = $block->translate->blocks_item;
if ($section) {
    $this->title = $section->title;
}
if ($parents) {
    $this->params['breadcrumbs'][] = ['label' => $block->translate->blocks_item, 'url' => ['index', 'block_id' => $block->id, 'section_id' => $parents[0]->section_id]];
    foreach ($parents as $parent) {
        $this->params['breadcrumbs'][] = ['label' => $parent->title, 'url' => ['index', 'block_id' => $block->id, 'section_id' => $parent->id]];
    }
} else if ($section) {
    $this->params['breadcrumbs'][] = ['label' => $block->translate->blocks_item, 'url' => ['index', 'block_id' => $block->id]];
}
$this->params['breadcrumbs'][] = $this->title;

//\thefx\blocks\assets\SortableJs\SortableJsAsset::register($this);
$settings = array_merge(Yii::$app->params['block'], Yii::$app->params['block' . $block->id] ?? []);
?>

<script>

    //document.addEventListener("DOMContentLoaded", function () {
    //
    //    function sortFolders(data) {
    //        if (data.prev.length !== 0) {
    //            var dataJson = {'type': 'after', 'item': data.id, 'node': data.prev.data('key')};
    //            $.get("<?php //=Url::to(['sort-category'])?>//", dataJson);
    //        } else if (data.next.length !== 0) {
    //            var dataJson = {'type': 'before', 'item': data.id, 'node': data.next.data('key')};
    //            $.get("<?php //=Url::to(['sort-category'])?>//", dataJson);
    //        }
    //    }
    //
    //    // Sortable
    //    document.querySelectorAll('table tbody').forEach(function (el) {
    //        Sortable.create(el, {
    //            draggable: 'tr[data-type="folder"]',
    //            handle: ".handle",
    //            onEnd: function (/**Event*/evt) {
    //                var data = {
    //                    'id'       : $(evt.item).data('key'),
    //                    'next'     : $(evt.item).next('tr[data-type="folder"]'),
    //                    'prev'     : $(evt.item).prev('tr[data-type="folder"]'),
    //                };
    //                sortFolders(data);
    //                // var itemEl = evt.item;  // dragged HTMLElement
    //                // evt.to;    // target list
    //                // evt.from;  // previous list
    //                // evt.oldIndex;  // element's old index within old parent
    //                // evt.newIndex;  // element's new index within new parent
    //                // evt.oldDraggableIndex; // element's old index within old parent, only counting draggable elements
    //                // evt.newDraggableIndex; // element's new index within new parent, only counting draggable elements
    //                // evt.clone // the clone element
    //                // evt.pullMode;  // when item is in another sortable: `"clone"` if cloning, `true` if moving
    //            },
    //        });
    //    });
    //
    //    // Sortable
    //    //document.querySelectorAll('table tbody').forEach(function (el) {
    //    //    Sortable.create(el, {
    //    //        draggable: 'tr[data-type="item"]',
    //    //        handle: ".handle",
    //    //        onEnd: function (/**Event*/evt) {
    //    //            var ids = [];
    //    //            $(evt.target).find('tr[data-type="item"]').each(function (key, element) {
    //    //                ids.push(element.getAttribute('data-key'));
    //    //            });
    //    //            $.get("<?////=Url::to(['sort-items'])?>////", {'ids': ids});
    //    //        },
    //    //    });
    //    //});
    //
    //});

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

<?php if (in_array(Yii::$app->user->id, $this->context->module->rootUsers, true)) :?>
    <div class="mb-3">
        <?= Html::a('<i class="fa fa-cog mr-1"></i>Элемент', ['block-fields/update', 'block_id' => $block->id, 'type' => BlockFields::TYPE_BLOCK_ITEM], ['class' => 'btn btn-default btn-sm']) ?>
        <?= Html::a('<i class="fa fa-cog mr-1"></i>Категории', ['block-fields/update', 'block_id' => $block->id, 'type' => BlockFields::TYPE_BLOCK_CATEGORY], ['class' => 'btn btn-default btn-sm']) ?>
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
            'content' => static function(BlockSections $row) {
                return $row->type === BlockSections::TYPE_ITEM ? Html::checkbox('selection[]', false, ['value' => $row->id]) : null;
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
                        'value' => static function(BlockSections $model) use ($block, $item) {
                            $imgPath = $item['value'] === 'photo' ? $model->getPhotoPath() : $model->getPhotoPreviewPath();
                            $img = Html::img($imgPath, ['style' => 'max-width:100px; max-height:100px']);
                            $url = $model->isFolder() ? ['index', 'block_id' => $block->id, 'section_id' => $model->id] : ['block-item/update', 'id' => $model->id];
                            return Html::a($img, $url, ['data-pjax' => '0']);
                        },
                    ];
                    break;
                case 'title':
                    $columns[] = [
                        'attribute' => 'title',
    //                'label' => '',
                        'format' => 'html',
                        'value' => static function(BlockSections $model) use ($block) {
                            if ($model->isFolder()) {
                                return '<i class="fa fa-folder text-muted position-left"></i> ' . Html::a($model->title, ['index', 'block_id' => $block->id, 'section_id' => $model->id]);
                            }
                            return Html::a($model->title, ['block-item/update', 'id' => $model->id], ['data-pjax' => '0']);
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
                        'content' => static function(BlockSections $row) {
                            return strip_tags($row->anons);
                        }
                    ];
                    break;
                case 'text':
                    $columns[] = [
                        'attribute' => 'text',
                        'content' => static function(BlockSections $row) {
                            return strip_tags($row->text);
                        }
                    ];
                    break;
                case 'public':
                    $columns[] = [
                        'attribute' => 'public',
                        'headerOptions' => ['style' => 'width:85px; text-align:center'],
                        'contentOptions' => ['style' => 'text-align:center'],
                        'content' => static function(BlockSections $row) {
                            return $row->public ? '<span class="badge badge-success">Да</span>' : '<span class="badge">Нет</span>';
                        }
                    ];
                    break;
                case 'update_date':
                    $columns[] = [
                        'attribute' => 'update_date',
                        'headerOptions' => ['style' => 'width:200px; text-align:center'],
                        'contentOptions' => ['style' => 'text-align:center'],
                        'content' => static function(BlockSections $row) {
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
            'template' => '{update}{delete}',
            'headerOptions' => ['style' => 'width:40px; text-align:center'],
            'contentOptions' => ['style' => 'text-align:center'],
            'urlCreator' => static function($action, BlockSections $model, $key, $index) use ($section) {
                $params = is_array($key) ? $key : ['id' => (string)$key];
//                $params['section_id'] = $section->id;
                $params[0] = ($model->isFolder() ? 'block-sections' : 'block-item') . '/' . $action;
                return Url::toRoute(array_filter($params));
            },
        ];
    ?>

<!--    --><?php //\yii\widgets\Pjax::begin(); ?>

    <?= Html::a($block->translate->block_create, ['block-item/create', 'block_id' => $block->id, 'section_id' => $section->id], ['class' => 'btn btn-success']) ?>

    <?php if ($settings['btn_add_group']) : ?>
        <?= Html::a($block->translate->category_create, ['create', 'block_id' => $block->id, 'section_id' => $section->id], ['class' => 'btn btn-default']) ?>
    <?php endif; ?>

    <?= $this->render('_search', ['model' => $searchModel]) ?>

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
