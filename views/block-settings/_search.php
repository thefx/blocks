<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model BlockSettingsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="block-settings-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'block_id') ?>

    <?= $form->field($model, 'upload_path') ?>

    <?= $form->field($model, 'photo_crop_width') ?>

    <?= $form->field($model, 'photo_crop_height') ?>

    <?php // echo $form->field($model, 'photo_crop_type') ?>

    <?php // echo $form->field($model, 'photo_preview_crop_width') ?>

    <?php // echo $form->field($model, 'photo_preview_crop_height') ?>

    <?php // echo $form->field($model, 'photo_preview_crop_type') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
