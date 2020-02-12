<?php

/** @var BlockItem $model */
/** @var Block $block */
/** @var BlockItemPropAssignments $form */
/** @var string $value - field name */

use app\shop\entities\Block\Block;
use app\shop\entities\Block\BlockItem;
use app\shop\entities\Block\BlockItemPropAssignments;
use app\widgets\cropper\FileInputCropper;
use app\widgets\switcher\SwitchInput;
use app\widgets\yii2CkeditorWidget\CKEditor;
use yii\helpers\Html;
use yii\jui\DatePicker;

$ckEditorOptions = (!$model->isNewRecord) ? [
    'options' => ['rows' => 6],
    'preset' => 'full',
    'enableKCFinder' => true,
    'kcfOptions' => [
        'uploadURL' => $model->getEditorPath(),
        'access' => [
            'files' => [
                'upload' => true,
                'delete' => true,
                'copy' => true,
                'move' => false,
                'rename' => true,
            ],
            'dirs' => [
                'create' => false,
                'delete' => false,
                'rename' => false,
            ],
        ],
        'types' => array(
            $model->getPrimaryKey()  =>  Yii::$app->params['editor.files'],
        ),
    ]
] : [
    'options' => ['rows' => 6],
    'preset' => 'full',
];

switch ($value) {
    case 'title':
        echo $form->field($model, 'title')->textInput(['maxlength' => true]);
        break;

    case 'date':
        echo $form->field($model, 'date')->widget(DatePicker::class);
        break;

    case 'path':
        echo $form->field($model, 'path')->textInput(['maxlength' => true]);
        break;

    case 'anons':
        echo $form->field($model, 'anons')->widget(CKEditor::class, $ckEditorOptions);
        break;

    case 'photo_preview':
        echo $form->field($model, 'photo_preview')->widget(FileInputCropper::class,[
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
                'imageUrl' => $model->getPhoto('photo') ? $model->getPhoto('photo') : '',
            ]
        ]);
        break;

    case 'photo':
        echo $form->field($model, 'photo')->widget(FileInputCropper::class,[
            'cropAttribute'=>'photo_crop',
            'cropConfig'=> [
                'savePath' => "{$block->settings->upload_path}",
                'dir' => "@app/web/upload/{$block->settings->upload_path}/",
                'urlDir' => "/{$block->settings->upload_path}",
                'defaultCrop' => [
                    $block->settings->photo_crop_width,
                    $block->settings->photo_crop_height,
                    $block->settings->photo_crop_type
                ],
                // только для поселков
                'crop' => array_filter($block->id == 12 ? [
                    [640, 1030, 'mobile', 'widen'],
                ] : [])
            ],
            'pluginOptions' => [
                'showUpload' => true,
                'browseLabel' => '',
                'removeLabel' => '',
                'mainClass' => 'input-group-lg',
                'imagePreview' => $model->getPhoto('photo') ? Html::img($model->getPhoto('photo')) : '',
                'imageUrl' => $model->getPhoto('photo') ? $model->getPhoto('photo') : '',
            ]
        ]);
        break;

    case 'parent_id':
        echo $form->field($model, 'parent_id')->dropDownList($model->categoryList(), [
            'placeholder' => 'Категория'
        ]);
        break;

    case 'public':
        echo $form->field($model, 'public')->widget(SwitchInput::class);
        break;

    case 'sort':
        echo $form->field($model, 'sort')->textInput();
        break;

    case 'text':
        echo $form->field($model, 'text')->widget(CKEditor::class, $ckEditorOptions);
        break;

    case 'seo_title':
        echo $form->field($model, 'seo_title')->textInput(['maxlength' => true]);
        break;

    case 'seo_keywords':
        echo $form->field($model, 'seo_keywords')->textarea(['rows' => 6]);
        break;

    case 'seo_description':
        echo $form->field($model, 'seo_description')->textarea(['rows' => 6]);
        break;
}
