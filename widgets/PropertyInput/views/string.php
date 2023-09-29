<?php

use thefx\blocks\models\BlockItemPropertyAssignment;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model BlockItemPropertyAssignment */
/* @var $form ActiveForm */
/* @var $attributeName string */
/* @var $label string */

?>

<div class="form-group">

    <?= HTML::label($label) ?>

    <?= Html::activeTextInput($model, $attributeName, ['class' => 'form-control']) ?>

    <?= Html::error($model, $attributeName, ['class' => 'help-block help-block-error']) ?>

</div>
