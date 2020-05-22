<?php

use app\widgets\switcher\SwitchInput;
use kartik\select2\Select2;
use thefx\blocks\forms\BlockFieldsCategoryForm;
use thefx\blocks\models\blocks\Block;
use thefx\blocks\models\blocks\BlockCategory;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model BlockCategory */
/* @var $block Block */
/* @var $form yii\widgets\ActiveForm */
/* @var $modelFieldsForm BlockFieldsCategoryForm */

?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<?= $form->errorSummary($model, ['class' => 'alert alert-danger']) ?>

<div class="card card-primary card-outline card-outline-tabs">

    <div class="card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="custom-tabs-1-tab" data-toggle="pill" href="#custom-tabs-1" role="tab" aria-controls="custom-tabs-1" aria-selected="true">Краткая информация</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="custom-tabs-2-tab" data-toggle="pill" href="#custom-tabs-2" role="tab" aria-controls="custom-tabs-2" aria-selected="false">Сео</a>
            </li>
        </ul>
    </div>

    <div class="card-body">

        <div class="tab-content">
            <div class="tab-pane fade active show" id="custom-tabs-1" role="tabpanel" aria-labelledby="custom-tabs-1-tab">

                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'path')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'anons')->widget(\vova07\imperavi\Widget::class, [
                        'settings' => [
                            'image' => [
                                'upload' => Url::to(['upload-image', 'id' => $model->id]),
                                'select' => Url::to(['get-uploaded-images', 'id' => $model->id])
                            ],
                        ]
                ]) ?>

                <?= $form->field($model, 'text')->widget(\vova07\imperavi\Widget::class, [
                    'settings' => [
                        'image' => [
                            'upload' => Url::to(['upload-image', 'id' => $model->id]),
                            'select' => Url::to(['get-uploaded-images', 'id' => $model->id])
                        ],
                    ]
                ]) ?>

                <?= $form->field($model, 'photo_preview')->widget(\app\widgets\cropper\FileInputCropper::class, [
                    'cropAttribute'=>'photo_preview_crop',
                    'cropConfig'=> [
                        'savePath' => $block->settings->upload_path,
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
                ]) ?>

<!--                --><?//= $form->field($model, 'date')->textInput() ?>

                <?= $form->field($model, 'parent_id')->widget(Select2::class, [
                    'data' => $model->categoryList(),
                    'options' => ['placeholder' => 'Категория'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]) ?>

                <?= $form->field($model, 'public')->widget(SwitchInput::class) ?>

                <?= $form->field($model, 'sort')->textInput() ?>

            </div>
            <div class="tab-pane fade" id="custom-tabs-2" role="tabpanel" aria-labelledby="custom-tabs-2-tab">

                <?= $form->field($model, 'seo_title')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'seo_keywords')->textarea(['rows' => 6]) ?>

                <?= $form->field($model, 'seo_description')->textarea(['rows' => 6]) ?>

            </div>
        </div>

    </div>

    <div class="card-footer clearfix">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

</div>

<?php ActiveForm::end(); ?>
