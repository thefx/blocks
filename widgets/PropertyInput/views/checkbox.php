<?php

use thefx\blocks\models\BlockItemPropertyAssignments;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $model BlockItemPropertyAssignments */
/* @var string $attributeName */

$propElements = $model->property->elements;
$propElementList = ArrayHelper::map($propElements, 'id', 'title');

?>

<div class="form-group">

    <?= Html::activeCheckbox($model, $attributeName, $propElementList) ?>
    <?= Html::error($model, $attributeName, ['class' => 'help-block help-block-error']) ?>

</div>
