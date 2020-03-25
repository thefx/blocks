<?php

use thefx\blocks\models\blocks\BlockPropElem;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model BlockPropElem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="block-prop-elem-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'block_prop_id')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <?= $form->field($model, 'default')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
