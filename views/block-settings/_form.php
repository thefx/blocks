<?php

use thefx\blocks\models\blocks\BlockSettings;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model BlockSettings */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="block-settings-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'upload_path')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'photo_crop_width')->textInput() ?>

    <?= $form->field($model, 'photo_crop_height')->textInput() ?>

    <?= $form->field($model, 'photo_crop_type')->dropDownList($model->listCropTypes()) ?>

    <?= $form->field($model, 'photo_preview_crop_width')->textInput() ?>

    <?= $form->field($model, 'photo_preview_crop_height')->textInput() ?>

    <?= $form->field($model, 'photo_preview_crop_type')->dropDownList($model->listCropTypes()) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
