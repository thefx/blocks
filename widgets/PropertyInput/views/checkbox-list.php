<?php

use thefx\blocks\models\BlockItemPropertyAssignments;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model BlockItemPropertyAssignments */
/* @var $form ActiveForm */
/* @var $attributeName string */
/* @var $label string */

$propElements = $model->property->elements;
$propElementList = ArrayHelper::map($propElements, 'id', 'title');

?>

<div class="form-group">

    <?= HTML::label($label) ?>

    <?= Html::activeCheckboxList($model, $attributeName, $propElementList, [
        'class' => 'checkboxes in-row margin-bottom-20',
        'item' => static function ($index, $label, $name, $checked, $value){
            return Html::checkbox($name, $checked, ['id' => 'check-' . $value, 'value' => $value]) . Html::label($label, 'check-' . $value);
        }
    ]) ?>

    <?= Html::error($model, $attributeName, ['class' => 'help-block help-block-error']) ?>

</div>
