<?php

/* @var $this yii\web\View */
/* @var $model BlockItem */
/* @var $block Block */
/* @var $category BlockCategory */
/* @var $parents BlockCategory[] */
/* @var $modelFieldsForm BlockFieldsItemForm */
/* @var $elem */

use thefx\blocks\models\blocks\Block;
use thefx\blocks\models\blocks\BlockCategory;
use thefx\blocks\forms\BlockFieldsItemForm;
use thefx\blocks\models\blocks\BlockItem;

$this->title = $block->translate->block_update;
foreach ($parents as $parent) {
    $title = $parent->isRoot() ? $block->title : $parent->title;
    $this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['block-category/index', 'parent_id' => $parent->id]];
}
if (!$category->isRoot()) {
    $this->params['breadcrumbs'][] = ['label' => $category->title, 'url' => ['block-category/index', 'parent_id' => $category->id]];
}
$this->params['breadcrumbs'][] = $this->title;
$this->params['title_btn'] = (Yii::$app->user->id == 1) ? $this->render('_modal', ['modelFieldsForm' => $modelFieldsForm]) : null; ?>

<div class="block-item-update">

    <?= $this->render('_form', [
        'model' => $model,
        'block' => $block,
        'elem' => $elem,
    ]) ?>

</div>
