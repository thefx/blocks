<?php

use thefx\blocks\models\blocks\BlockItemPropAssignments;
use yii\helpers\Html;

/* @var $model BlockItemPropAssignments */
/* @var string $attributeName */

//$model->{$attributeName} = ($model->{$attributeName} !== null) ? $model->{$attributeName} : 1;

echo HTML::label($model->prop->title);
echo Html::activeTextInput($model, $attributeName, ['class' => 'form-control']);

//echo '<br />' . HTML::activeRadioList($model, $attributeName, [0 => 'Нет', 1 => 'Да'], [
//    'radioTemplate' => '{input}',
//    'item' => function($index, $label, $name, $checked, $value) {
//
//        $checked = ($checked) ? 'checked' : '';
//        $active = ($checked) ? 'active' : '';
//
//        $return = '<label class="btn btn-default '.$active.'">';
//        $return .= '<input type="radio" name="'.$name.'" value="'.$value.'" '.$checked.'>' . ucwords($label);
//        $return .= '</label>';
//
//        return $return;
//    },
//    'class' => 'btn-group',
//    'data-toggle' => 'buttons',
//]);