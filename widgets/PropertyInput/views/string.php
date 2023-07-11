<?php

use thefx\blocks\models\BlockItemPropertyAssignments;
use yii\helpers\Html;

/* @var $model BlockItemPropertyAssignments */
/* @var $attributeName string  */

?>

<div class="form-group">

    <?= HTML::label($model->property->title) ?>

    <?= Html::activeTextInput($model, $attributeName, ['class' => 'form-control']) ?>

    <?= Html::error($model, $attributeName, ['class' => 'help-block help-block-error']) ?>

</div>

