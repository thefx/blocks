<?php

use thefx\blocks\models\forms\search\BlockSectionsSearch;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model BlockSectionsSearch */
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

<?= $form->field($model, 'section_id', ['template' => '{input}'])->hiddenInput() ?>

<div class="input-group mb-3">
    <?= $form->field($model, 'title', ['template' => '{input}'])->textInput(['placeholder' => 'Поиск']) ?>

    <div class="input-group-append">
        <?= Html::submitButton('<i class="fas fa-search"></i>', ['class' => 'btn btn-default']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

