<?php

/* @var $this View */
/* @var $model BlockPropertyElement */
/* @var $form ActiveForm */
/* @var $index int */

use thefx\blocks\models\BlockPropertyElement;
use yii\web\View;
use yii\widgets\ActiveForm;

?>

<td class="text-center handle" style="width: 70px">
    <?= $model->id ?>
    <?= $form->field($model, "[$index]id")->hiddenInput(['maxlength' => true])->label(false) ?>
</td>

<td class="">
    <?= $form->field($model, "[$index]title")->textInput(['maxlength' => true])->label(false) ?>
</td>

<td class="">
    <?= $form->field($model, "[$index]code")->textInput(['maxlength' => true])->label(false) ?>
</td>

<td class="text-center" style="width: 120px">
<!--    --><?//= $form->field($model, "[$index]sort")->textInput()->label(false) ?>
    <?= $model->sort ?>
</td>

<td class="text-center" style="width: 150px">
    <?= $form->field($model, "[$index]default", ['template' => '{input}'])->checkbox(['class' => 'default_value']) ?>
</td>

<td class="text-center">
    <button class="btn btn-sm btn-danger del-item"><i class="fa fa-trash"></i></button>
</td>