<?php

use thefx\blocks\models\BlockProperty;

/* @var $this yii\web\View */
/* @var $model BlockProperty */

$this->title = 'Редактировать свойство';
$this->params['breadcrumbs'][] = ['label' => 'Блоки', 'url' => ['block/index']];
$this->params['breadcrumbs'][] = ['label' => $model->block->title, 'url' => ['block/view', 'id' => $model->block_id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="block-property-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
