<?php

use thefx\blocks\models\blocks\BlockItemPropAssignments;
use vova07\imperavi\Widget;
use yii\helpers\Html;

/* @var $model BlockItemPropAssignments */
/* @var $form yii\widgets\ActiveForm */
/* @var string $attributeName */

echo Html::label($model->prop->title);
//echo Html::activeTextarea($model, $attributeName, ['class' => 'form-control', 'rows' => 6]);
echo $model->prop->redactor
    ? $form->field($model, $attributeName)->widget(Widget::class, [])->label(false)
    : $form->field($model, $attributeName)->textarea(['class' => 'form-control', 'rows' => 6])->label(false);
