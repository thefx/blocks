<?php

/* @var $this yii\web\View */
/* @var $model BlockItem */
/* @var $block Block */
/* @var $category BlockCategory */
/* @var $parents BlockCategory[] */
/* @var $elem */
/* @var $modelFieldsForm BlockFieldsItemForm */

use thefx\blocks\forms\BlockFieldsItemForm;
use thefx\blocks\models\blocks\Block;
use thefx\blocks\models\blocks\BlockCategory;
use thefx\blocks\models\blocks\BlockItem;

$this->title = $block->translate->block_create;
foreach ($parents as $parent) {
    $title = $parent->isRoot() ? $block->title : $parent->title;
    $this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['block-category/index', 'parent_id' => $parent->id]];
}
if (!$category->isRoot()) {
    $this->params['breadcrumbs'][] = ['label' => $category->title, 'url' => ['block-category/index', 'parent_id' => $category->id]];
}
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="block-item-create">

    <?= $this->render('_form', [
        'model' => $model,
        'block' => $block,
        'elem' => $elem,
    ]) ?>

</div>
