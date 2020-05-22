<?php

use thefx\blocks\forms\search\BlockCategorySearch;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model BlockCategorySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    'options' => [
        'data-pjax' => 1,
        'class' => 'form-inline',
        'style' => 'margin-top: 20px',
    ],
    'fieldConfig' => [
        'options' => [
            'tag' => false,
        ],
    ],
]); ?>

<?= $form->field($model, 'block_id', ['template' => '{input}'])->hiddenInput() ?>

<?= $form->field($model, 'parent_id', ['template' => '{input}'])->hiddenInput() ?>

<div class="input-group mb-3">
    <?= $form->field($model, 'title', ['template' => '{input}'])->textInput(['placeholder' => 'Поиск']) ?>

    <div class="input-group-append">
        <?= Html::submitButton('<i class="fas fa-search"></i>', ['class' => 'btn btn-default']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

