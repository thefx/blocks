<?php

/* @var $this yii\web\View */
/* @var $model BlockItem */
/* @var $block Block */
/* @var $category BlockSections */
/* @var $parents BlockSections[] */
/* @var $modelFieldsForm BlockFieldsItemForm */
/* @var $elem */

use thefx\blocks\forms\BlockFieldsItemForm;
use thefx\blocks\models\Block;
use thefx\blocks\models\BlockItem;
use thefx\blocks\models\BlockSections;

$this->title = $block->translate->block_update;
$this->params['breadcrumbs'][] = ['label' => $block->translate->blocks_item, 'url' => ['block-sections/index', 'block_id' => $model->block_id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="block-item-update">

    <?= $this->render('_form', [
        'model' => $model,
        'block' => $block,
        'elem' => $elem,
    ]) ?>

</div>
