<?php

/* @var BlockItem $model */
/* @var $form yii\widgets\ActiveForm */
/* @var int $value - propId */

use thefx\blocks\widgets\propInput\PropInput;
use thefx\blocks\models\blocks\BlockItem;
use thefx\dropzoneWidget\DropzoneWidget;

/** @var thefx\blocks\models\blocks\BlockItemPropAssignments $assignment */
$assignment = $model->getAssignmentByPropId($value);

//try {
if ($assignment->prop->type === 'image') {
    echo $form->field($assignment, "[{$value}]value")->widget(DropzoneWidget::class, [
        'extraData' => ['propId' => $assignment->prop->id]
    ])->label($assignment->prop->title);
} else {
    echo $form->field($assignment, "[{$value}]value")->widget(PropInput::class)->label(false);
}
echo $form->field($assignment, "[{$value}]prop_id")->hiddenInput()->label(false);
//} catch (Error $error) {
//    var_dump($assignment);
//    var_dump($value);
//    var_dump($error);
//}
