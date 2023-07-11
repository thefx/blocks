<?php

/* @var $this yii\web\View */
/* @var $model BlockSections */
/* @var $block Block */
/* @var $category BlockSections */
/* @var $parents BlockSections[] */

use thefx\blocks\models\Block;
use thefx\blocks\models\BlockSections;

$this->title = $block->translate->category_create;
?>

<div class="block-category-create">

    <?= $this->render('_form', [
        'parents' => $parents,
        'model' => $model,
        'block' => $block,
    ]) ?>

</div>
