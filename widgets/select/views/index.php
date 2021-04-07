<?php

/* @var ActiveRecord $model */
/* @var array $data */
/* @var string $attributeName */

use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\web\View;

echo '<div class="form-group">';
echo Html::activeDropDownList($model, $attributeName, $data, [
    'class' => 'form-control',
    'style'=> 'width: 100%;',
]);
echo '</div>';

$inputId = Html::getInputId($model, $attributeName);
$this->registerJs(" $('#{$inputId}').select2({placeholder: '', allowClear: true});", View::POS_READY);

