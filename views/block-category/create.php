<?php

/* @var $this yii\web\View */
/* @var $model app\shop\entities\Block\BlockCategory */
/* @var $block Block */
/* @var $category BlockCategory */
/* @var $parents BlockCategory[] */

use app\shop\entities\Block\Block;
use app\shop\entities\Block\BlockCategory;

$this->title = $block->translate->category_create;
if ($parents) {
    $this->params['breadcrumbs'][] = ['label' => $block->translate->categories, 'url' => ['index', 'parent_id' => $parents[0]->parent_id]];
    foreach ($parents as $parent) {
        $this->params['breadcrumbs'][] = ['label' => $parent->title, 'url' => ['block-category/index', 'parent_id' => $parent->id]];
    }
} else {
    $this->params['breadcrumbs'][] = ['label' => $block->translate->categories, 'url' => ['index', 'parent_id' => $category->id]];
}
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="block-category-create">

    <?= $this->render('_form', [
        'model' => $model,
        'block' => $block,
    ]) ?>

</div>
