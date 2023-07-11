<?php

use thefx\blocks\models\BlockItemPropertyAssignments;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var BlockItemPropertyAssignments $model */
/* @var string $attributeName */
/* @var string $debug */

$propElements = $model->property->elements;
$propElementList = ArrayHelper::map($propElements, 'id', 'title');

?>

<div class="form-group row">
    <div class="col-sm-3">
        <?= HTML::label($model->property->title) ?>
    </div>
    <div class="col-sm-9">
        <?= HTML::activeRadioList($model, $attributeName, $propElementList, [
        //    'radioTemplate' => '{input}',
            'item' => static function ($index, $label, $name, $checked, $value) use ($debug) {
                $label = $debug ? $value . ' ' . $label : $label;
                $checked = $checked ? 'checked' : '';
                $active = $checked ? 'active' : '';
                $return = '<label class="btn btn-default ' . $active . '">';
                $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" ' . $checked . '>' . ucwords($label);
                $return .= '</label>';
                return $return;
            },
            'class' => 'btn-group btn-group-toggle',
            'data-toggle' => 'buttons',
        ]) ?>
        <?= Html::error($model, $attributeName, ['class' => 'help-block help-block-error']) ?>
    </div>
</div>
