<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\forms\BlockPropSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="block-prop-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'block_id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'public') ?>

    <?php // echo $form->field($model, 'multi') ?>

    <?php // echo $form->field($model, 'need') ?>

    <?php // echo $form->field($model, 'sort') ?>

    <?php // echo $form->field($model, 'code') ?>

    <?php // echo $form->field($model, 'in_filter') ?>

    <?php // echo $form->field($model, 'hint') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
