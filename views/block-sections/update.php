<?php

/* @var $this yii\web\View */
/* @var $model BlockSection */
/* @var $block Block */
/* @var $category BlockSection */
/* @var $parents BlockSection[] */

use thefx\blocks\models\Block;
use thefx\blocks\models\BlockSection;

$this->title = $block->translate->category_update . ' : ' . $model->title;

?>

<div class="block-category-update">

    <?= $this->render('_form', [
        'model' => $model,
        'block' => $block,
        'parents' => $parents,
    ]) ?>

</div>
