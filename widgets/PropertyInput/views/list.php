<?php

use kartik\select2\Select2;
use thefx\blocks\models\BlockItemPropertyAssignments;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $model BlockItemPropertyAssignments */
/* @var $form ActiveForm */
/* @var $attributeName string */

$propertyElements = $model->property->elements;
$propertyElementList = ArrayHelper::map($propertyElements, 'id', 'title');

?>

<div class="form-group">

    <?= Html::label($model->property->title) ?>

<!--    --><?php //= Select2::widget([
//        'model' => $model,
//        'bsVersion' => '4.x',
//        'attribute' => $attributeName,
//        'data' => $propertyElementList,
//        'options' => [
//            'prompt' => 'Не выбрано',
//            'multiple' => $model->property->isMultiple()
//        ],
//        'pluginOptions' => [
//            'allowClear' => true,
////            'tags' => true,
//        ],
//    ]) ?>
<!---->
<!--    --><?php //= Html::error($model, $attributeName, ['class' => 'invalid-feedback']) ?>

    <?= $form->field($model, $attributeName)->widget(Select2::class, [
        'data' => $propertyElementList,
        'bsVersion' => '4.x',
//        'theme' => Select2::THEME_KRAJEE,
        'options' => [
            'placeholder' => '',
            'multiple' => $model->property->isMultiple(),
        ],
        'pluginOptions' => [
            'allowClear' => ! $model->property->isMultiple(),
//            'tags' => true,
        ],
    ])->label(false) ?>

</div>
