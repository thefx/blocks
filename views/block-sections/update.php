<?php

/* @var $this yii\web\View */
/* @var $model BlockSections */
/* @var $block Block */
/* @var $category BlockSections */
/* @var $parents BlockSections[] */

use thefx\blocks\models\Block;
use thefx\blocks\models\BlockSections;

$this->title = $block->translate->category_update . ' : ' . $model->title;

?>

<div class="block-category-update">

    <?= $this->render('_form', [
        'model' => $model,
        'block' => $block,
        'parents' => $parents,
    ]) ?>

</div>
