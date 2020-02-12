<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\shop\entities\Block\BlockSettings */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Block Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-settings-view">

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
            'upload_path',
            'photo_crop_width',
            'photo_crop_height',
            'photo_crop_type',
            'photo_preview_crop_width',
            'photo_preview_crop_height',
            'photo_preview_crop_type',
        ],
    ]) ?>

</div>
