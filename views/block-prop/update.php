<?php

use thefx\blocks\models\blocks\BlockProp;

/* @var $this yii\web\View */
/* @var $model BlockProp */

$this->title = 'Редактировать свойство';
$this->params['breadcrumbs'][] = ['label' => 'Блоки', 'url' => ['block/index']];
$this->params['breadcrumbs'][] = ['label' => $model->block->title, 'url' => ['block/view', 'id' => $model->block_id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="block-prop-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
