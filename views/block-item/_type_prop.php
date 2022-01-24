<?php

/* @var BlockItem $model */
/* @var $form yii\widgets\ActiveForm */
/* @var int $value - propId */

use thefx\blocks\widgets\propInput\PropInput;
use thefx\blocks\models\blocks\BlockItem;

/** @var thefx\blocks\models\blocks\BlockItemPropAssignments $assignment */
$assignment = $model->getAssignmentByPropId($value);

echo $form->field($assignment, "[{$value}]value")->widget(PropInput::class)->label(false);
echo $form->field($assignment, "[{$value}]prop_id")->hiddenInput()->label(false);
