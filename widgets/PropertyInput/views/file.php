<?php

/* @var $model BlockItemPropertyAssignments */
/* @var $form ActiveForm */
/* @var string $attributeName */

use thefx\blocks\models\BlockItemPropertyAssignments;
use thefx\blocks\widgets\DropzoneWidget\DropzoneWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<div class="form-group">

    <?= Html::label($model->property->title) ?>

    <?= $form->field($model, $attributeName)->widget(DropzoneWidget::class, [
            'extraData' => ['propertyId' => $model->property->id],
            'uploadUrl' => Url::to(['add-file']),
            'acceptedFiles' => $model->property->file_type ?: null,
        ])->label(false /*$assignment->prop->title*/)
    ?>

    <?= Html::error($model, $attributeName, ['class' => 'invalid-feedback']) ?>

</div>