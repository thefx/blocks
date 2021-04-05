<?php

use thefx\blocks\models\blocks\Block;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model Block */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="block-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

<!--    --><?//= $form->field($model, 'path')->textInput(['maxlength' => true]) ?>

<!--    --><?//= $form->field($model, 'table')->textInput(['maxlength' => true]) ?>

<!--    --><?//= $form->field($model, 'template')->textInput(['maxlength' => true]) ?>

<!--    --><?//= $form->field($model, 'pagination')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
