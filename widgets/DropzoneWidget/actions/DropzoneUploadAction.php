<?php

namespace thefx\blocks\widgets\DropzoneWidget\actions;

use thefx\blocks\models\BlockProperty;
use thefx\blocks\widgets\DropzoneWidget\forms\UploadFileForm;
use Yii;
use yii\base\Action;
use yii\web\Response;

/**
 * https://www.yiiframework.com/doc/guide/2.0/ru/input-file-upload
 *
 *   Example params config
 *
 *  'property' => [
 *      'crop' => [
 *          [1280, 785, ''],
 *          [247, 247, 'list_'],
 *          [150, 150, 'prev_'],
 *      ]
 *  ],
 *  // For property with ID 20
 *  'property_20' => [
 *      crop' => [
 *          [800, 400, ''],
 *          [247, 247, 'list_'],
 *          [150, 150, 'prev_'],
 *      ]
 *  ],
 */
class DropzoneUploadAction extends Action
{
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $property = BlockProperty::findOne(['id' => Yii::$app->request->post('propertyId')]);
        $params = array_merge(Yii::$app->params['blockProperty'], Yii::$app->params['blockProperty' . $property->id] ?? []);

        $form = new UploadFileForm([
            'extensions' => $property->file_type,
            'fileAttribute' => 'file',
            'path' => $params['dir'],
            'url' => $params['urlDir'],
            'crop' => $params['crop'],
            'resizeQuality' => $params['resizeQuality'],
        ]);

        $form->load(Yii::$app->request->post());

        try {
            return $form->uploadFile();
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            return ['errors' => $e->getMessage()];
        }
    }
}
