<?php

use thefx\blocks\models\BlockItem;
use thefx\blocks\models\BlockItemPropertyAssignments;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $model BlockItemPropertyAssignments */
/* @var $form ActiveForm */
/* @var $attributeName string */
/* @var $label string */

$relBlockItemList = $model->prop->getAssignBlockCatList();
$blockItems = BlockItem::find()->where(['section_id' => $model->value])->all();

$inputId = Html::getInputId($model, $attributeName);
$js = "$('#{$inputId}').select2(/*{placeholder: '', allowClear: true}*/);";
$this->registerJs($js, View::POS_READY);

?>

<div class="form-group">

    <?= HTML::label($label) ?>

    <?= Html::activeDropDownList($model, $attributeName, $relBlockItemList, [
        'class' => 'form-control select2',
        'style'=> 'width: 100%;',
        'multiple' => $model->property->isMultiple(),
        'prompt' => ! $model->property->isMultiple() ? 'Не выбрано' : null
    ]) ?>

</div>
