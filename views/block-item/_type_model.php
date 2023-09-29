<?php

use thefx\blocks\models\BlockItem;
use thefx\blocks\models\BlockItemPropertyAssignment;
use thefx\blocks\widgets\Select\Select2Input;
use thefx\blocks\widgets\Switcher\SwitchInput;
use thefx\widgetsCropper\FileInputCropper;
use vova07\imperavi\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\DatePicker;

/** @var BlockItem $model */
/** @var BlockItemPropertyAssignment $form */
/** @var string $value - field name */
/** @var string $label - field label */

if (!$model->hasProperty($value)) {
    return 'property ' . $value . ' not found';
}

$settings = array_merge(Yii::$app->params['blockItem'], Yii::$app->params['blockItem' . $model->block_id] ?? []);

$label = $label ?: $model->getAttributeLabel($value);

switch ($value) {
    case 'seo_title':
    case 'alias':
    case 'title':
        echo $form->field($model, $value)->textInput(['maxlength' => true])->label($label);
        break;

    case 'date':
        echo $form->field($model, $value)->widget(DatePicker::class)->label($label);
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
        ])->label($label);
        break;

    case 'photo_preview':
        echo $form->field($model, $value)->widget(FileInputCropper::class, [
            'cropAttribute' => 'photo_preview_crop',
            'cropConfig' => [
                'savePath' => $settings['photo_preview']['urlDir'],
                'dir' => $settings['photo_preview']['dir'],
                'urlDir' => $settings['photo_preview']['urlDir'],
                'defaultCrop' => $settings['photo_preview']['defaultCrop'],
                'crop' => $settings['photo']['crop'],
            ],
            'pluginOptions' => [
                'showUpload' => true,
                'browseLabel' => '',
                'removeLabel' => '',
                'mainClass' => 'input-group-lg',
                'imagePreview' => $model->getPhotoPreviewPath('min_') ? Html::img($model->getPhotoPreviewPath('min_')) : '',
                'imageUrl' => $model->getPhotoPreviewPath() ?: '',
            ]
        ])->label($label);
        break;

    case 'photo':
        echo $form->field($model, $value)->widget(FileInputCropper::class, [
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
                'imagePreview' => $model->getPhotoPath('min_') ? Html::img($model->getPhotoPath('min_')) : '',
                'imageUrl' => $model->getPhotoPath() ?: '',
            ]
        ])->label($label);
        break;

    case 'section_id':
        echo $form->field($model, $value)->widget(Select2Input::class, [
            'data' => $model->getSectionList(),
            'options' => ['placeholder' => 'Категория'],
            'pluginOptions' => [
                'allowClear' => false,
            ],
        ])->label($label);
        break;

    case 'public':
        echo $form->field($model, $value)->widget(SwitchInput::class)->label($label);
        break;

    case 'sort':
        echo $form->field($model, $value)->textInput()->label($label);
        break;

    case 'seo_description':
    case 'seo_keywords':
        echo $form->field($model, $value)->textarea(['rows' => 6])->label($label);
        break;
    default:
        echo 'property ' . $value . ' not found';
}
