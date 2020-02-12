<?php

/* @var BlockItem $model */
/* @var $form yii\widgets\ActiveForm */
/* @var int $value - propId */

use app\modules\admin\widgets\propInput\PropInput;
use app\shop\entities\Block\BlockItem;

$assignment = $model->getAssignmentByPropId($value);

echo $form->field($assignment, "[{$value}]value")->widget(PropInput::class)->label(false);
echo $form->field($assignment, "[{$value}]prop_id")->hiddenInput()->label(false);
