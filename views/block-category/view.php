<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\shop\entities\Block\BlockCategory */

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
            'path',
            'anons:ntext',
            'text:ntext',
            'photo',
            'photo_preview',
            'date',
            'parent_id',
            'lft',
            'rgt',
            'depth',
            'create_user',
            'create_date',
            'update_user',
            'update_date',
            'public',
        ],
    ]) ?>

</div>
