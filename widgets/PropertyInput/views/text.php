<?php

use thefx\blocks\models\BlockItemPropertyAssignments;
use yii\helpers\Html;
use vova07\imperavi\Widget;

/* @var BlockItemPropertyAssignments $model */
/* @var string $attributeName */
/* @var yii\widgets\ActiveForm $form */

?>

<div class="form-group">

    <?= HTML::label($model->property->title) ?>

    <?php if ($model->property->redactor) : ?>
        <?= $form->field($model, $attributeName)->widget(Widget::class, [])->label(false) ?>
    <?php else : ?>
        <?= $form->field($model, $attributeName)->textarea(['class' => 'form-control', 'rows' => 6])->label(false) ?>
    <?php endif; ?>

    <?= Html::error($model, $attributeName, ['class' => 'help-block help-block-error']) ?>

</div>


