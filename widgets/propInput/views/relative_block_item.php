<?php

use thefx\blocks\models\blocks\BlockItem;
use thefx\blocks\models\blocks\BlockItemPropAssignments;
use yii\helpers\Html;
use yii\web\View;

/* @var $model BlockItemPropAssignments */
/* @var string $attributeName */

$relBlockItemList = $model->prop->getAssignBlockItemList();
$blockItems = BlockItem::find()->where(['parent_id' => $model->value])->all();

$inputId = Html::getInputId($model, $attributeName);
$js = "$('#{$inputId}').select2(/*{placeholder: '', allowClear: true}*/);";
$this->registerJs($js, View::POS_READY);

?>

<div class="form-group">

    <?= HTML::label($model->prop->title) ?>
    <?= Html::activeDropDownList($model, $attributeName, $relBlockItemList, [
        'class' => 'form-control select2',
        'style'=> 'width: 100%;',
        'multiple' => $model->prop->isMulti(),
        'prompt' => ! $model->prop->isMulti() ? 'Не выбрано' : null
    ]) ?>

</div>
