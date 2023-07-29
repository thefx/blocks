<?php

/* @var $model BlockItem */
/* @var $form yii\widgets\ActiveForm */
/* @var $value int - propId */
/* @var $label */
/* @var $assignment BlockItemPropertyAssignments */

use thefx\blocks\models\BlockItemPropertyAssignments;
use thefx\blocks\widgets\PropertyInput\PropertyInputWidget;
use thefx\blocks\models\BlockItem;

$assignments = $model->getAssignmentsByPropertyId($value);

$label = $label ?: reset($assignments)->property->title;
$type = reset($assignments)->property->type;

//echo '<pre>';
//print_r($assignments);

echo '<div class="input-wrapper" data-property-type="' . $type . '" data-property-id="' . $value . '">';
foreach ($assignments as $assignmentId => $assignment) {
//    var_dump(['key' => $key]);
//    var_dump(['$assignment' => $assignment->attributes]);
    try {
        echo $form->field($assignment, "[$value][$assignmentId]value", ['template' => '{input}'])->widget(PropertyInputWidget::class, [
            'type' => $type,
            'label' => $label,
//        'debug' => true,
        ]);
    } catch (Error $error) {
        var_dump($error);
        var_dump($value);
        var_dump($assignment);
    }
}
echo '</div>';
