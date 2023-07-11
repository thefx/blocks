<?php

/* @var $this yii\web\View */
/* @var $model BlockProperty */

use thefx\blocks\models\BlockProperty;
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Block Props', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-property-view">

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
            'block_id',
            'title',
            'type',
            'public',
            'multi',
            'need',
            'sort',
            'code',
            'hint',
        ],
    ]) ?>

</div>
