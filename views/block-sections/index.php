<?php

use thefx\blocks\models\Block;
use thefx\blocks\models\BlockField;
use thefx\blocks\models\BlockSection;
use thefx\blocks\models\forms\search\BlockSectionSearch;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel BlockSectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $block Block */
/* @var $section BlockSection */
/* @var $parents BlockSection[] */

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

$settings = array_merge(Yii::$app->params['block'], Yii::$app->params['block' . $block->id] ?? []);
$bsModel = new BlockSection();

?>

<div class="block-category-index">

    <?php
        $columns = [];
        $columns[] = [
            'class' => CheckboxColumn::class,
            'headerOptions' => ['style' => 'width:40px; text-align:center'],
            'contentOptions' => ['style' => 'text-align:center'],
            'content' => static function(BlockSection $row) {
                return $row->type === BlockSection::TYPE_ITEM ? Html::checkbox('selection[]', false, ['value' => $row->id]) : null;
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
                        'attribute' => $item['value'],
                        'label' => $item['name'] ?: $bsModel->getAttributeLabel($item['value']),
                        'headerOptions' => ['style' => 'width:140px; text-align:center'],
                        'contentOptions' => ['style' => 'width:140px; text-align:center'],
                        'format' => 'html',
                        'value' => static function(BlockSection $model) use ($block, $item) {
                            $imgPath = $item['value'] === 'photo' ? $model->getPhotoPath('min_') : $model->getPhotoPreviewPath('min_');
                            $img = Html::img($imgPath, ['style' => 'max-width:100px; max-height:100px']);
                            $url = $model->isFolder() ? ['index', 'block_id' => $block->id, 'section_id' => $model->id] : ['block-item/update', 'id' => $model->id];
                            return Html::a($img, $url, ['data-pjax' => '0']);
                        },
                    ];
                    break;
                case 'title':
                    $columns[] = [
                        'attribute' => 'title',
                        'label' => $item['name'] ?: $bsModel->getAttributeLabel($item['value']),
                        'format' => 'html',
                        'value' => static function(BlockSection $model) use ($block) {
                            if ($model->isFolder()) {
                                return '<i class="fa fa-folder text-muted position-left"></i> ' . Html::a($model->title, ['index', 'block_id' => $block->id, 'section_id' => $model->id]);
                            }
                            return Html::a($model->title, ['block-item/update', 'id' => $model->id], ['data-pjax' => '0']);
                        },
                    ];
                    break;
                case 'sort':
                    $columns[] = [
                        'attribute' => 'sort',
                        'label' => $item['name'] ?: $bsModel->getAttributeLabel($item['value']),
                        'headerOptions' => ['style' => 'width:85px; text-align:center'],
                        'contentOptions' => ['style' => 'text-align:center'],
                    ];
                    break;
                case 'date':
                    $columns[] = [
                        'attribute' => 'date',
                        'label' => $item['name'] ?: $bsModel->getAttributeLabel($item['value']),
                    ];
                    break;
                case 'anons':
                    $columns[] = [
                        'attribute' => 'anons',
                        'label' => $item['name'] ?: $bsModel->getAttributeLabel($item['value']),
                        'content' => static function(BlockSection $row) {
                            return strip_tags($row->anons);
                        }
                    ];
                    break;
                case 'text':
                    $columns[] = [
                        'attribute' => 'text',
                        'label' => $item['name'] ?: $bsModel->getAttributeLabel($item['value']),
                        'content' => static function(BlockSection $row) {
                            return strip_tags($row->text);
                        }
                    ];
                    break;
                case 'public':
                    $columns[] = [
                        'attribute' => 'public',
                        'label' => $item['name'] ?: $bsModel->getAttributeLabel($item['value']),
                        'headerOptions' => ['style' => 'width:85px; text-align:center'],
                        'contentOptions' => ['style' => 'text-align:center'],
                        'content' => static function(BlockSection $row) {
                            return $row->public ? '<span class="badge badge-success">Да</span>' : '<span class="badge">Нет</span>';
                        }
                    ];
                    break;
                case 'create_date':
                case 'update_date':
                    $columns[] = [
                        'attribute' => $item['value'],
                        'label' => $item['name'] ?: $bsModel->getAttributeLabel($item['value']),
                        'headerOptions' => ['style' => 'width:200px; text-align:center'],
                        'contentOptions' => ['style' => 'text-align:center'],
                        'content' => static function(BlockSection $row) use ($item){
                            return $row->{$item['value']} ? date('d.m.Y H:i:s', strtotime($row->{$item['value']})) : null;
                        }
                    ];
                    break;
                case 'id':
                    $columns[] = [
                        'attribute' => 'id',
                        'label' => $item['name'] ?: $bsModel->getAttributeLabel($item['value']),
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
            'urlCreator' => static function($action, BlockSection $model, $key, $index) use ($section) {
                $params = is_array($key) ? $key : ['id' => (string)$key];
//                $params['section_id'] = $section->id;
                $params[0] = ($model->isFolder() ? 'block-sections' : 'block-item') . '/' . $action;
                return Url::toRoute(array_filter($params));
            },
        ];
    ?>

<!--    --><?php //\yii\widgets\Pjax::begin(); ?>

    <div class="d-flex">
        <div>

            <?= Html::a($block->translate->block_create, ['block-item/create', 'block_id' => $block->id, 'section_id' => $section->id], ['class' => 'btn btn-success']) ?>

            <?php if ($settings['btn_add_group']) : ?>
                <?= Html::a($block->translate->category_create, ['create', 'block_id' => $block->id, 'section_id' => $section->id], ['class' => 'btn btn-default']) ?>
            <?php endif; ?>

        </div>
        <div class="ml-auto">

            <?php if (in_array(Yii::$app->user->id, $this->context->module->rootUsers, true)) :?>
                <?= Html::a('<i class="fa fa-cog mr-1"></i>Элемент', ['block-fields/update', 'block_id' => $block->id, 'type' => BlockField::TYPE_BLOCK_ITEM], ['class' => 'btn btn-default btn-sm']) ?>
                <?= Html::a('<i class="fa fa-cog mr-1"></i>Категории', ['block-fields/update', 'block_id' => $block->id, 'type' => BlockField::TYPE_BLOCK_CATEGORY], ['class' => 'btn btn-default btn-sm']) ?>
            <?php endif; ?>

        </div>
    </div>

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
