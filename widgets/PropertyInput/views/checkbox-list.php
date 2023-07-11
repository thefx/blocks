<?php

use backend\models\ContentPropertyAssignments;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var ContentPropertyAssignments $model */
/* @var string $attributeName */

$propElements = $model->property->elements;
$propElementList = ArrayHelper::map($propElements, 'id', 'title');

?>

<div class="form-group">

    <?= HTML::label($model->property->title, ['class' => 'col-sm-2 col-form-label']) ?>
    <?= Html::activeCheckboxList($model, $attributeName, $propElementList, [
        'class' => 'checkboxes in-row margin-bottom-20',
        'item' => static function ($index, $label, $name, $checked, $value){
            return Html::checkbox($name, $checked, ['id' => 'check-' . $value, 'value' => $value]) . Html::label($label, 'check-' . $value);
        }
    ]) ?>
    <?= Html::error($model, $attributeName, ['class' => 'help-block help-block-error']) ?>

</div>