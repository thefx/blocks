<?php

use thefx\blocks\models\blocks\BlockItemPropAssignments;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/* @var $model BlockItemPropAssignments */
/* @var string $attributeName */

//$model->{$attributeName} = ($model->{$attributeName} !== null) ? $model->{$attributeName} : 1;

$propElements = $model->prop->elements;

//usort($propElements, function (BlockPropElem $a, BlockPropElem $b) {
//    if ($a->sort == $b->sort) return 0;
//    return $a->sort > $b->sort;
//});

$propElementList = ArrayHelper::map($propElements, 'id', 'title');

echo '<div class="form-group">';
echo HTML::label($model->prop->title);
//echo Html::activeDropDownList($model, $attributeName, $propElementList, ['class' => 'form-control']);
echo Html::activeDropDownList($model, $attributeName, $propElementList, ['class' => 'form-control', 'style'=> 'width: 100%;', 'multiple' => $model->prop->isMulti(), 'prompt' => $model->prop->isRequired() ? null : 'Не выбрано']);
echo '</div>';

$inputId = Html::getInputId($model, $attributeName);
$this->registerJs(" $('#{$inputId}').select2();", View::POS_READY);

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