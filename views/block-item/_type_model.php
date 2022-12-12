<?php

use thefx\blocks\widgets\select\Select2Input;
use thefx\widgetsCropper\FileInputCropper;
use thefx\blocks\widgets\switcher\SwitchInput;
use thefx\blocks\models\blocks\Block;
use thefx\blocks\models\blocks\BlockItem;
use thefx\blocks\models\blocks\BlockItemPropAssignments;
use vova07\imperavi\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\DatePicker;

/** @var BlockItem $model */
/** @var Block $block */
/** @var BlockItemPropAssignments $form */
/** @var string $value - field name */

if (!$model->hasProperty($value)) {
    return '';
}

switch ($value) {
    case 'seo_title':
    case 'path':
    case 'title':
    case 'article':
    case 'price':
    case 'price_old':
    case 'currency':
    case 'unit':
        echo $form->field($model, $value)->textInput(['maxlength' => true]);
        break;

    case 'date':
        echo $form->field($model, $value)->widget(DatePicker::class);
        break;

    case 'text':
    case 'anons':
        echo $form->field($model, $value)->widget(Widget::class, [
            'settings' => [
                'image' => [
                    'upload' => Url::to(['upload-image', 'id' => $model->id]),
                    'select' => Url::to(['get-uploaded-images', 'id' => $model->id])
                ],
            ]
        ]);
        break;

    case 'photo_preview':
        echo $form->field($model, $value)->widget(FileInputCropper::class,[
            'cropAttribute'=>'photo_preview_crop',
            'cropConfig'=> [
                'savePath' => $block->settings->upload_path,
                'dir' => "@webroot/upload/{$block->settings->upload_path}/",
                'urlDir' => "/{$block->settings->upload_path}",
                'defaultCrop' => [
                    $block->settings->photo_preview_crop_width,
                    $block->settings->photo_preview_crop_height,
                    $block->settings->photo_preview_crop_type
                ],
            ],
            'pluginOptions' => [
                'showUpload' => true,
                'browseLabel' => '',
                'removeLabel' => '',
                'mainClass' => 'input-group-lg',
                'imagePreview' => $model->getPhoto($value) ? Html::img($model->getPhoto($value)) : '',
                'imageUrl' => $model->getPhoto($value) ?: '',
            ]
        ]);
        break;

    case 'photo':
        echo $form->field($model, $value)->widget(FileInputCropper::class,[
            'cropAttribute'=>'photo_crop',
            'cropConfig'=> [
                'savePath' => $block->settings->upload_path,
                'dir' => "@webroot/upload/{$block->settings->upload_path}/",
                'urlDir' => '/' . $block->settings->upload_path,
                'defaultCrop' => [
                    $block->settings->photo_crop_width,
                    $block->settings->photo_crop_height,
                    $block->settings->photo_crop_type
                ],
                // только для поселков
//                'crop' => array_filter($block->id == 12 ? [
//                    [640, 1030, 'mobile', 'widen'],
//                ] : [])
            ],
            'pluginOptions' => [
                'showUpload' => true,
                'browseLabel' => '',
                'removeLabel' => '',
                'mainClass' => 'input-group-lg',
                'imagePreview' => $model->getPhoto() ? Html::img($model->getPhoto()) : '',
                'imageUrl' => $model->getPhoto() ?: '',
            ]
        ]);
        break;

    case 'parent_id':
        echo $form->field($model, $value)->widget(Select2Input::class, [
            'data' => $model->categoryList(),
            'options' => ['placeholder' => 'Категория'],
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]);
        break;

    case 'public':
        echo $form->field($model, $value)->widget(SwitchInput::class);
        break;

    case 'sort':
        echo $form->field($model, $value)->textInput();
        break;

    case 'seo_description':
    case 'seo_keywords':
        echo $form->field($model, $value)->textarea(['rows' => 6]);
        break;

}
