<?php

/* @var BlockItem $model */
/* @var $form yii\widgets\ActiveForm */
/* @var int $value - propId */

use thefx\blocks\widgets\propInput\PropInput;
use thefx\blocks\models\blocks\BlockItem;

/** @var thefx\blocks\models\blocks\BlockItemPropAssignments $assignment */
$assignment = $model->getAssignmentByPropId($value);

//try {
    echo $form->field($assignment, "[{$value}]value")->widget(PropInput::class)->label(false);
    echo $form->field($assignment, "[{$value}]prop_id")->hiddenInput()->label(false);
//} catch (Error $error) {
//    var_dump($assignment);
//    var_dump($value);
//    var_dump($error);
//}
