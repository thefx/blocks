<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\shop\entities\Block\BlockProp */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Block Props', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-prop-view">

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
            'in_filter',
            'hint',
        ],
    ]) ?>

</div>
