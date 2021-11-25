<?php

use thefx\blocks\models\blocks\BlockTranslate;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model BlockTranslate */

$this->title = 'Переводы';
$this->params['breadcrumbs'][] = ['label' => 'Блоки', 'url' => ['block/index']];
$this->params['breadcrumbs'][] = ['label' => $model->block->title, 'url' => ['block/view', 'block_id' => $model->block_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-translate-update">

    <div class="block-translate-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'category')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'categories')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'block_item')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'blocks_item')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'block_create')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'block_update')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'block_delete')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'category_create')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'category_update')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'category_delete')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
