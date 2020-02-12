<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\shop\entities\Block\BlockPropElem */

$this->title = 'Update Block Prop Elem: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Block Prop Elems', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="block-prop-elem-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
