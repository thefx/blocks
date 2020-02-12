<?php

use app\shop\entities\Block\BlockCategory;
use app\shop\entities\Block\BlockItem;
use app\shop\entities\Block\BlockItemPropAssignments;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $model BlockItemPropAssignments */
/* @var string $attributeName */

//$model->{$attributeName} = ($model->{$attributeName} !== null) ? $model->{$attributeName} : 1;

$relBlockItemList = $model->prop->getAssignBlockItemList();
$blockItems = BlockItem::find()->where(['parent_id' => $model->value])->all();

$inputId = Html::getInputId($model, $attributeName);
$this->registerJs(" $('#{$inputId}').select2(/*{placeholder: '', allowClear: true}*/);", View::POS_READY);

?>

<div class="form-group">

    <?= HTML::label($model->prop->title); ?>
    <?= Html::activeDropDownList($model, $attributeName, $relBlockItemList, ['class' => 'form-control select2', 'style'=> 'width: 100%;', 'multiple' => $model->prop->isMulti(), 'prompt' => 'Не выбрано']); ?>

</div>
