<?php

use thefx\blocks\models\BlockItemPropertyAssignment;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $model BlockItemPropertyAssignment */
/* @var $form ActiveForm */
/* @var $attributeName string */
/* @var $label string */

$relBlockItemList = $model->property->getAssignBlockItemsList();

$inputId = Html::getInputId($model, $attributeName);
$js = "$('#{$inputId}').select2(/*{placeholder: '', allowClear: true}*/);";
$this->registerJs($js, View::POS_READY);

?>

<div class="form-group">

    <?= HTML::label($label) ?>

    <?= \kartik\select2\Select2::widget([
        'model' => $model,
        'attribute' => $attributeName,
        'data' => $relBlockItemList,
        'options' => [
            'placeholder' => 'Выберите ...',
            'multiple' => $model->property->isMultiple()
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>

</div>
