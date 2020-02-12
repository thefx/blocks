<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\shop\entities\Block\BlockSettings */

$this->title = 'Редактировать настройки фото';
$this->params['breadcrumbs'][] = ['label' => 'Блоки', 'url' => ['block/index']];
$this->params['breadcrumbs'][] = ['label' => $model->block->title, 'url' => ['block/view', 'id' => $model->block_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-settings-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
