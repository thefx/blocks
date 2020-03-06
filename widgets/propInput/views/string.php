<?php

use thefx\blocks\models\blocks\BlockItemPropAssignments;
use yii\helpers\Html;

/* @var $model BlockItemPropAssignments */
/* @var string $attributeName */

echo HTML::label($model->prop->title);
echo Html::activeTextInput($model, $attributeName, ['class' => 'form-control']);
