<?php

use thefx\blocks\models\blocks\BlockItemPropAssignments;
use vova07\imperavi\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $model BlockItemPropAssignments */
/* @var $form yii\widgets\ActiveForm */
/* @var string $attributeName */

echo Html::label($model->prop->title);
//echo Html::activeTextarea($model, $attributeName, ['class' => 'form-control', 'rows' => 6]);
echo $model->prop->redactor
    ? $form->field($model, $attributeName)->widget(Widget::class, [
        'settings' => [
            'image' => [
                'upload' => Url::to(['upload-image', 'id' => $model->block_item_id]),
                'select' => Url::to(['get-uploaded-images', 'id' => $model->block_item_id])
            ],
        ]
    ])->label(false)
    : $form->field($model, $attributeName)->textarea(['class' => 'form-control', 'rows' => 6])->label(false);
