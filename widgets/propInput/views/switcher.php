<?php

use thefx\blocks\models\blocks\BlockItemPropAssignments;
use yii\helpers\Html;

/* @var $model BlockItemPropAssignments */
/* @var string $attributeName */

if ($model->value === null) {
    $model->value = (int) $model->prop->default_value;
}

echo HTML::label($model->prop->title);
echo Html::activeRadioList($model, $attributeName, [0=> 'Нет', 1 => 'Да']);
