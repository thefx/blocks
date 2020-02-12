<?php

use app\shop\forms\Block\BlockFieldsCategoryForm;
use app\widgets\switcher\SwitchInput;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\shop\entities\Block\BlockCategory */
/* @var $form yii\widgets\ActiveForm */
/* @var $modelFieldsForm BlockFieldsCategoryForm */

?>

<div class="block-category-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>

    <div class="nav-tabs-custom goods-form">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab_anons">Краткая информация</a></li>
<!--            <li><a data-toggle="tab" href="#tab_detail">Подробная информация</a></li>-->
<!--            <li><a data-toggle="tab" href="#tab_relations">Связи</a></li>-->
            <li><a data-toggle="tab" href="#tab_seo">Сео</a></li>
<!--            <li><a data-toggle="tab" href="#tab_other">Прочее</a></li>-->
        </ul>
        <div class="tab-content">
            <div id="tab_anons" class="tab-pane active">

<!--                --><?//= $form->field($model, 'block_id')->textInput() ?>

                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'path')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'anons')->textarea(['rows' => 6]) ?>

                <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

<!--                --><?//= $form->field($model, 'photo')->textInput(['maxlength' => true]) ?>

<!--                --><?//= $form->field($model, 'photo_preview')->textInput(['maxlength' => true]) ?>

<!--                --><?//= $form->field($model, 'date')->textInput() ?>

                <?= $form->field($model, 'parent_id')->widget(Select2::class, [
                    'data' => $model->categoryList(),
                    'options' => ['placeholder' => 'Категория'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>

                <?= $form->field($model, 'public')->widget(SwitchInput::class) ?>

            </div>
            <div id="tab_detail" class="tab-pane"></div>
            <div id="tab_relations" class="tab-pane"></div>
            <div id="tab_seo" class="tab-pane">

                <?= $form->field($model, 'seo_title')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'seo_keywords')->textarea(['rows' => 6]) ?>

                <?= $form->field($model, 'seo_description')->textarea(['rows' => 6]) ?>

            </div>
            <div id="tab_other" class="tab-pane"></div>
        </div>

        <div class="panel-body">
            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>
