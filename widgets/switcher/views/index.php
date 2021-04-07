<?php

/* @var ActiveRecord $model */

/* @var string $attributeName */

use yii\db\ActiveRecord;
use yii\helpers\Html;

//$model->{$attributeName} = ($model->{$attributeName} !== null) ? $model->{$attributeName} : 1;

$this->registerCss("
    .btn-default:not(:disabled):not(.disabled).active, 
    .btn-default:not(:disabled):not(.disabled):active, 
    .show>.btn-default.dropdown-toggle {
        color: #fff;
        background-color: #28a745;
        border-color: #28a745;
    }
");

echo '<br />' . HTML::activeRadioList($model, $attributeName, [0 => 'Нет', 1 => 'Да'], [
//    'radioTemplate' => '{input}',
        'item' => static function ($index, $label, $name, $checked, $value) {

            $checked = $checked ? 'checked' : '';
            $active = $checked ? 'active' : '';

            $return = '<label class="btn btn-default ' . $active . '">';
            $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" ' . $checked . '>' . ucwords($label);
            $return .= '</label>';

            return $return;
        },
        'class' => 'btn-group btn-group-toggle',
        'data-toggle' => 'buttons',
    ]);
