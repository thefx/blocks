<?php

use kartik\select2\Select2;
use thefx\blocks\models\BlockItemPropertyAssignment;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $model BlockItemPropertyAssignment */
/* @var $form ActiveForm */
/* @var $attributeName string */
/* @var $label string */

$propertyElements = (new \yii\db\Query())
    ->select('value')
    ->from(BlockItemPropertyAssignment::tableName())
    ->where(['property_id' => $model->property_id])
    ->all();

$propertyElementList = ArrayHelper::map($propertyElements, 'value', 'value');

$this->registerCss("
    .select2-container--krajee-bs4 .select2-selection--multiple .select2-selection__choice__remove {
        padding: 3px 3px 0 0.2rem;
    }
", [], 'pi.list');

?>

<div class="form-group">

    <?= HTML::label($label) ?>

    <?= $form->field($model, $attributeName, ['enableClientValidation' => false])->widget(Select2::class, [
        'data' => $propertyElementList,
        'bsVersion' => '4.x',
        'options' => [
            'placeholder' => '',
            'multiple' => $model->property->isMultiple(),
        ],
        'pluginOptions' => [
            'allowClear' => ! $model->property->isMultiple(),
//            'ajax' => [
//                'url' => \yii\helpers\Url::to(['get-tags']),
//                'dataType' => 'json',
//                'data' => new \yii\web\JsExpression('function(params) {return {q:params.term}; }')
//            ],
            'tags' => true,
        ]
    ])->label(false) ?>

</div>
