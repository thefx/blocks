<?php

/* @var $this yii\web\View */
/* @var $model app\shop\entities\Block\BlockItem */
/* @var $block Block */
/* @var $category BlockCategory */
/* @var $parents BlockCategory[] */
/* @var $elem */
/* @var $modelFieldsForm BlockFieldsItemForm */

use app\shop\entities\Block\Block;
use app\shop\entities\Block\BlockCategory;
use app\shop\forms\Block\BlockFieldsItemForm;

$this->title = $block->translate->block_create;
foreach ($parents as $parent) {
    $title = $parent->isRoot() ? $block->title : $parent->title;
    $this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['block-category/index', 'parent_id' => $parent->id]];
}
if (!$category->isRoot()) {
    $this->params['breadcrumbs'][] = ['label' => $category->title, 'url' => ['block-category/index', 'parent_id' => $category->id]];
}
$this->params['breadcrumbs'][] = $this->title;
$this->params['title_btn'] = (Yii::$app->user->id == 1) ? $this->render('_modal', ['modelFieldsForm' => $modelFieldsForm]) : null; ?>

<div class="block-item-create">

    <?= $this->render('_form', [
        'model' => $model,
        'block' => $block,
        'elem' => $elem,
    ]) ?>

</div>
