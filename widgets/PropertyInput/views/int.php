<?php

use thefx\blocks\models\BlockItemPropertyAssignments;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model BlockItemPropertyAssignments */
/* @var $form ActiveForm */
/* @var $attributeName string */
/* @var $label string */

?>

<div class="form-group">

    <?= HTML::label($label) ?>

    <?= Html::activeTextInput($model, $attributeName, ['class' => 'form-control']) ?>

    <?= Html::error($model, $attributeName, ['class' => 'invalid-feedback']) ?>

<!--    --><?php //= $form->field($model, $attributeName)->textInput() ?>

</div>
