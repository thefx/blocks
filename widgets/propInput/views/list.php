<?php

use thefx\blocks\models\blocks\BlockItemPropAssignments;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/* @var $model BlockItemPropAssignments */
/* @var string $attributeName */

$propElements = $model->prop->elements;

//usort($propElements, function (BlockPropElem $a, BlockPropElem $b) {
//    if ($a->sort == $b->sort) return 0;
//    return $a->sort > $b->sort;
//});

$propElementList = ArrayHelper::map($propElements, 'id', 'title');

echo '<div class="form-group">';
echo HTML::label($model->prop->title);
echo Html::activeDropDownList($model, $attributeName, $propElementList, [
    'class' => 'form-control',
    'style'=> 'width: 100%;',
    'multiple' => $model->prop->isMulti(),
    'prompt' => $model->prop->isRequired() ? null : 'Не выбрано'
]);
echo '</div>';

$inputId = Html::getInputId($model, $attributeName);
$this->registerJs(" $('#{$inputId}').select2();", View::POS_READY);
