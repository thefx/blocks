<?php

use app\widgets\switcher\SwitchInput;
use kartik\select2\Select2;
use thefx\blocks\forms\BlockFieldsCategoryForm;
use thefx\blocks\models\blocks\Block;
use thefx\blocks\models\blocks\BlockCategory;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model BlockCategory */
/* @var $block Block */
/* @var $form yii\widgets\ActiveForm */
/* @var $modelFieldsForm BlockFieldsCategoryForm */


//var_dump($model->getPhoto('photo_preview'));
//die;
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

                <?= $form->field($model, 'photo_preview')->widget(\app\widgets\cropper\FileInputCropper::class, [
                    'cropAttribute'=>'photo_preview_crop',
                    'cropConfig'=> [
                        'savePath' => "{$block->settings->upload_path}",
                        'dir' => "@app/web/upload/{$block->settings->upload_path}/",
                        'urlDir' => "/{$block->settings->upload_path}",
                        'defaultCrop' => [
                            $block->settings->photo_preview_crop_width,
                            $block->settings->photo_preview_crop_height,
                            $block->settings->photo_preview_crop_type
                        ],
        //                'crop' => [
        //                    [300, 300, 'min', 'fit'],
        //                ]
                    ],
                    'pluginOptions' => [
                        'showUpload' => true,
                        'browseLabel' => '',
                        'removeLabel' => '',
                        'mainClass' => 'input-group-lg',
                        'imagePreview' => $model->getPhoto('photo_preview') ? Html::img($model->getPhoto('photo_preview')) : '',
                        'imageUrl' => $model->getPhoto('photo_preview') ? $model->getPhoto('photo_preview') : '',
                    ]
                ]); ?>


<!--                --><?//= $form->field($model, 'date')->textInput() ?>

                <?= $form->field($model, 'parent_id')->widget(Select2::class, [
                    'data' => $model->categoryList(),
                    'options' => ['placeholder' => 'Категория'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>

                <?= $form->field($model, 'public')->widget(SwitchInput::class) ?>

                <?= $form->field($model, 'sort')->textInput() ?>

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
