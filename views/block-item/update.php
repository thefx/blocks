<?php

/* @var $this yii\web\View */
/* @var $model BlockItem */
/* @var $block Block */
/* @var $category BlockCategory */
/* @var $parents BlockCategory[] */
/* @var $modelFieldsForm BlockFieldsItemForm */
/* @var $elem */
/* @var $template array */

use thefx\blocks\models\blocks\Block;
use thefx\blocks\models\blocks\BlockCategory;
use thefx\blocks\forms\BlockFieldsItemForm;
use thefx\blocks\models\blocks\BlockItem;

$this->title = $model->isItem() ? $block->translate->block_update : 'Изменить серию';

foreach ($parents as $parent) {
    $title = $parent->isRoot() ? $block->title : $parent->title;
    $this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['block-category/index', 'parent_id' => $parent->id]];
}
if (!$category->isRoot()) {
    $this->params['breadcrumbs'][] = ['label' => $category->title, 'url' => ['block-category/index', 'parent_id' => $category->id]];
}
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_modal_edit_file_info') ?>

<div class="block-item-update">

    <?= $this->render('_form', [
        'model' => $model,
        'block' => $block,
        'elem' => $elem,
        'template' => $template,
    ]) ?>

</div>
