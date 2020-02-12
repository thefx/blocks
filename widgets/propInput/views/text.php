<?php

use app\shop\entities\Block\BlockItemPropAssignments;
use app\widgets\yii2CkeditorWidget\CKEditor;
use yii\helpers\Html;

/* @var $model BlockItemPropAssignments */
/* @var string $attributeName */

$ckEditorOptions = [
    'options' => ['rows' => 6],
    'preset' => 'full',
];

echo Html::label($model->prop->title);
//echo Html::activeTextarea($model, $attributeName, ['class' => 'form-control', 'rows' => 6]);
echo $model->prop->redactor
    ? $form->field($model, $attributeName)->widget(CKEditor::class, $ckEditorOptions)->label(false)
    : $form->field($model, $attributeName)->textarea(['class' => 'form-control', 'rows' => 6])->label(false);
