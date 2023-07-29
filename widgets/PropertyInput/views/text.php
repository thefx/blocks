<?php

use thefx\blocks\models\BlockItemPropertyAssignments;
use yii\helpers\Html;
use vova07\imperavi\Widget;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $model BlockItemPropertyAssignments */
/* @var $form ActiveForm */
/* @var $attributeName string */
/* @var $label string */

?>

<div class="form-group">

    <?= HTML::label($label) ?>

    <?php if ($model->property->redactor) : ?>
        <?= $form->field($model, $attributeName)->widget(Widget::class, [
            'settings' => [
                'image' => [
                    'upload' => Url::to(['upload-image', 'id' => $model->block_item_id]),
                    'select' => Url::to(['get-uploaded-images', 'id' => $model->block_item_id])
                ],
            ]
        ])->label(false) ?>
    <?php else : ?>
        <?= $form->field($model, $attributeName)->textarea(['class' => 'form-control', 'rows' => 6])->label(false) ?>
    <?php endif; ?>

    <?= Html::error($model, $attributeName, ['class' => 'help-block help-block-error']) ?>

</div>
