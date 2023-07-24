<?php

/* @var $model BlockItemPropertyAssignments */
/* @var $form ActiveForm */
/* @var string $attributeName */

use thefx\blocks\models\BlockItemPropertyAssignments;
use thefx\blocks\widgets\DropzoneWidget\DropzoneWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$cropParams = array_merge(Yii::$app->params['blockProperty']['crop'], Yii::$app->params['blockProperty' . $model->property->id]['crop'] ?? []);
$maxDimension = max(ArrayHelper::getColumn($cropParams, 0) + ArrayHelper::getColumn($cropParams, 1));
?>

<div class="form-group">

    <?= Html::label($model->property->title) ?>

    <?= $form->field($model, $attributeName)->widget(DropzoneWidget::class, [
            'extraData' => ['propertyId' => $model->property->id],
            'uploadUrl' => Url::to(['add-file']),
            'acceptedFiles' => $model->property->file_type ?: null,
            'maxFiles' => $model->property->isMultiple() ? null : 1,
            'resizeWidth' => $maxDimension,
            'resizeHeight' => $maxDimension,
        ])->label(false /*$assignment->prop->title*/)
    ?>

    <?= Html::error($model, $attributeName, ['class' => 'invalid-feedback']) ?>

</div>