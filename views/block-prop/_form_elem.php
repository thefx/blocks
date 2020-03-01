<?php

/* @var $this yii\web\View */
/* @var $model BlockPropElem */
/* @var $form yii\widgets\ActiveForm */
/* @var $index int */

use thefx\blocks\models\blocks\BlockPropElem; ?>

<td class="col-sm-1 text-center handle">
    <?= $model->id ?>
    <?= $form->field($model, "[$index]id")->hiddenInput(['maxlength' => true])->label(false) ?>
</td>

<td class="col-sm-6">
    <?= $form->field($model, "[$index]title")->textInput(['maxlength' => true])->label(false) ?>
</td>

<td class="col-sm-2">
    <?= $form->field($model, "[$index]code")->textInput(['maxlength' => true])->label(false) ?>
</td>

<td class="col-sm-1 text-center">
<!--    --><?//= $form->field($model, "[$index]sort")->textInput()->label(false) ?>
    <?= $model->sort ?>
</td>

<td class="col-sm-1 text-center">
    <?= $form->field($model, "[$index]default")->checkbox(['class' => 'default_value'])->label(false) ?>
</td>

<td class="col-sm-1 text-center">
    <button class="btn btn-sm btn-danger del-item"><i class="fa fa-trash"></i></button>
</td>