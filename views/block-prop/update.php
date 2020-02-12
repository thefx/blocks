<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\shop\entities\Block\BlockProp */

$this->title = 'Редактировать свойство';
$this->params['breadcrumbs'][] = ['label' => 'Блоки', 'url' => ['block/index']];
$this->params['breadcrumbs'][] = ['label' => $model->block->title, 'url' => ['block/view', 'id' => $model->block_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-prop-update">

    <?= $this->render('_form', [
        'model' => $model,
        'elem' => $elem,
    ]) ?>

</div>
