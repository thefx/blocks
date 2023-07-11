<?php

namespace thefx\blocks\widgets\DropzoneWidget;

use thefx\blocks\assets\DropzoneJs\DropzoneJsAsset;
use thefx\blocks\assets\SortableJs\SortableJsAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class DropzoneWidgetAsset extends AssetBundle
{
    public $depends = [
        SortableJsAsset::class,
        DropzoneJsAsset::class,
        JqueryAsset::class,
    ];
}
