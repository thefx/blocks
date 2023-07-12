<?php

use thefx\blocks\models\BlockSections;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model BlockSections */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Block Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-category-view">

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
            'alias',
            'anons:ntext',
            'text:ntext',
            'photo',
            'photo_preview',
            'date',
            'section_id',
            'left',
            'right',
            'depth',
            'create_user',
            'create_date',
            'update_user',
            'update_date',
            'public',
        ],
    ]) ?>

</div>