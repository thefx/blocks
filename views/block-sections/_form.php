<?php

use kartik\select2\Select2;
use thefx\blocks\forms\BlockFieldsCategoryForm;
use thefx\blocks\models\Block;
use thefx\blocks\models\BlockSections;
use thefx\blocks\widgets\Select\Select2Input;
use thefx\blocks\widgets\Switcher\SwitchInput;
use thefx\widgetsCropper\FileInputCropper;
use vova07\imperavi\Widget;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model BlockSections */
/* @var $block Block */
/* @var $form ActiveForm */
/* @var $modelFieldsForm BlockFieldsCategoryForm */
/* @var $parents array */

if ($parents) {
    $this->params['breadcrumbs'][] = ['label' => $block->translate->blocks_item, 'url' => ['index', 'block_id' => $model->block_id, 'section_id' => $parents[0]->section_id]];
    foreach ($parents as $parent) {
        $this->params['breadcrumbs'][] = ['label' => $parent->title, 'url' => ['block-category/index', 'section_id' => $parent->id]];
    }
} else {
    $this->params['breadcrumbs'][] = ['label' => $block->translate->blocks_item, 'url' => ['index', 'block_id' => $model->block_id]];
}
$this->params['breadcrumbs'][] = $this->title;

$settings = array_merge(Yii::$app->params['block'], Yii::$app->params['block' . $model->block_id] ?? []);
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<?= $form->errorSummary($model, ['class' => 'alert alert-danger']) ?>

<div class="card card-primary card-outline card-outline-tabs">

    <div class="card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="custom-tabs-1-tab" data-toggle="pill" href="#custom-tabs-1" role="tab"
                   aria-controls="custom-tabs-1" aria-selected="true">Краткая информация</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="custom-tabs-2-tab" data-toggle="pill" href="#custom-tabs-2" role="tab"
                   aria-controls="custom-tabs-2" aria-selected="false">Сео</a>
            </li>
        </ul>
    </div>

    <div class="card-body">

        <div class="tab-content">
            <div class="tab-pane fade active show" id="custom-tabs-1" role="tabpanel"
                 aria-labelledby="custom-tabs-1-tab">

                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'anons')->widget(Widget::class, [
                    'settings' => [
                        'image' => [
                            'upload' => Url::to(['upload-image', 'id' => $model->id]),
                            'select' => Url::to(['get-uploaded-images', 'id' => $model->id])
                        ],
                    ]
                ]) ?>

                <?= $form->field($model, 'text')->widget(Widget::class, [
                    'settings' => [
                        'image' => [
                            'upload' => Url::to(['upload-image', 'id' => $model->id]),
                            'select' => Url::to(['get-uploaded-images', 'id' => $model->id])
                        ],
                    ]
                ]) ?>

                <?= $form->field($model, 'photo_preview')->widget(FileInputCropper::class, [
                    'cropAttribute' => 'photo_preview_crop',
                    'cropConfig' => [
                        'savePath' => $settings['photo_preview']['urlDir'],
                        'dir' => $settings['photo_preview']['dir'],
                        'urlDir' => $settings['photo_preview']['urlDir'],
                        'defaultCrop' => $settings['photo_preview']['defaultCrop'],
                        'crop' => $settings['photo_preview']['crop'],
                    ],
                    'pluginOptions' => [
                        'showUpload' => true,
                        'browseLabel' => '',
                        'removeLabel' => '',
                        'mainClass' => 'input-group-lg',
                        'imagePreview' => $model->getPhotoPreviewPath() ? Html::img($model->getPhotoPreviewPath()) : '',
                        'imageUrl' => $model->getPhotoPreviewPath() ?: '',
                    ]
                ]) ?>

                <?= $form->field($model, 'photo')->widget(FileInputCropper::class, [
                    'cropAttribute' => 'photo_crop',
                    'cropConfig' => [
                        'savePath' => $settings['photo']['urlDir'],
                        'dir' => $settings['photo']['dir'],
                        'urlDir' => $settings['photo']['urlDir'],
                        'defaultCrop' => $settings['photo']['defaultCrop'],
                        'crop' => $settings['photo']['crop'],
                    ],
                    'pluginOptions' => [
                        'showUpload' => true,
                        'browseLabel' => '',
                        'removeLabel' => '',
                        'mainClass' => 'input-group-lg',
                        'imagePreview' => $model->getPhotoPath() ? Html::img($model->getPhotoPath()) : '',
                        'imageUrl' => $model->getPhotoPath() ?: '',
                    ]
                ]) ?>

                <?= $form->field($model, 'section_id')->widget(Select2Input::class, [
                    'data' => BlockSections::getSectionsList($model->block_id, $model->id),
//                    ['prompt' => 'Выберите раздел ...'],
//                    'data' => $model->categoryList(),
//                    'options' => ['placeholder' => 'Категория'],
                    'options' => ['prompt' => 'Выберите раздел ...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'prompt' => 'Выберите раздел ...'
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
