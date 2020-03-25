<?php

use thefx\blocks\models\blocks\BlockPropElem;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model BlockPropElem */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Block Prop Elems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-prop-elem-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'block_prop_id',
            'title',
            'code',
            'sort',
            'default',
        ],
    ]) ?>

</div>
