<?php

/* @var $this yii\web\View */
/* @var $model app\shop\entities\Block\BlockCategory */
/* @var $block app\shop\entities\Block\Block */
/* @var $category app\shop\entities\Block\BlockCategory */
/* @var $parents app\shop\entities\Block\BlockCategory[] */

$this->title = $block->translate->category_update . ' : ' . $model->title;
if ($parents) {
    $this->params['breadcrumbs'][] = ['label' => $block->translate->categories, 'url' => ['index', 'parent_id' => $parents[0]->parent_id]];
    foreach ($parents as $parent) {
        $this->params['breadcrumbs'][] = ['label' => $parent->title, 'url' => ['block-category/index', 'parent_id' => $parent->id]];
    }
} else {
    $this->params['breadcrumbs'][] = ['label' => $block->translate->categories, 'url' => ['index', 'parent_id' => $category->id]];
}
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="block-category-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
