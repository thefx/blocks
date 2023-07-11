<?php

use thefx\blocks\models\BlockItem;
use thefx\blocks\models\BlockItemPropertyAssignments;
use thefx\blocks\widgets\Select\Select2Input;
use thefx\blocks\widgets\Switcher\SwitchInput;
use thefx\widgetsCropper\FileInputCropper;
use vova07\imperavi\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\DatePicker;

/** @var BlockItem $model */
/** @var BlockItemPropertyAssignments $form */
/** @var string $value - field name */

if (!$model->hasProperty($value)) {
    return 'property ' . $value . ' not found';
}

$settings = array_merge(Yii::$app->params['blockItem'], Yii::$app->params['blockItem' . $model->block_id] ?? []);

switch ($value) {
    case 'seo_title':
    case 'alias':
    case 'title':
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
        echo $form->field($model, $value)->widget(FileInputCropper::class, [
            'cropAttribute' => 'photo_preview_crop',
            'cropConfig' => [
                'savePath' => $settings['photo_preview']['urlDir'],
                'dir' => $settings['photo_preview']['dir'],
                'urlDir' => $settings['photo_preview']['urlDir'],
                'defaultCrop' => $settings['photo_preview']['defaultCrop'],
            ],
            'pluginOptions' => [
                'showUpload' => true,
                'browseLabel' => '',
                'removeLabel' => '',
                'mainClass' => 'input-group-lg',
                'imagePreview' => $model->getPhotoPreviewPath() ? Html::img($model->getPhotoPreviewPath()) : '',
                'imageUrl' => $model->getPhotoPreviewPath() ?: '',
            ]
        ]);
        break;

    case 'photo':
        echo $form->field($model, $value)->widget(FileInputCropper::class, [
            'cropAttribute' => 'photo_crop',
            'cropConfig' => [
                'savePath' => $settings['photo']['urlDir'],
                'dir' => $settings['photo']['dir'],
                'urlDir' => $settings['photo']['urlDir'],
                'defaultCrop' => $settings['photo']['defaultCrop'],
            ],
            'pluginOptions' => [
                'showUpload' => true,
                'browseLabel' => '',
                'removeLabel' => '',
                'mainClass' => 'input-group-lg',
                'imagePreview' => $model->getPhotoPath() ? Html::img($model->getPhotoPath()) : '',
                'imageUrl' => $model->getPhotoPath() ?: '',
            ]
        ]);
        break;

    case 'section_id':
        echo $form->field($model, $value)->widget(Select2Input::class, [
            'data' => $model->getSectionList(),
            'options' => ['placeholder' => 'Категория'],
            'pluginOptions' => [
                'allowClear' => false,
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
    default:
        echo 'property ' . $value . ' not found';
}
