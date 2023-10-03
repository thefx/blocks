<?php

/* @var $this yii\web\View */
/* @var $model BlockSection */
/* @var $block Block */
/* @var $category BlockSection */
/* @var $parents BlockSection[] */

use thefx\blocks\models\Block;
use thefx\blocks\models\BlockSection;

$this->title = $block->translate->category_create;
?>

<div class="block-category-create">

    <?= $this->render('_form', [
        'parents' => $parents,
        'model' => $model,
        'block' => $block,
    ]) ?>

</div>
