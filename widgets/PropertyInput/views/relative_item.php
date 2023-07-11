<?php

use backend\models\ContentPropertyAssignments;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $model ContentPropertyAssignments */
/* @var $form ActiveForm */
/* @var $attributeName string */

$relBlockItemList = $model->property->getAssignBlockItemsList();

$inputId = Html::getInputId($model, $attributeName);
$js = "$('#{$inputId}').select2(/*{placeholder: '', allowClear: true}*/);";
$this->registerJs($js, View::POS_READY);

?>

<div class="form-group">

    <?= HTML::label($model->property->title) ?>

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
