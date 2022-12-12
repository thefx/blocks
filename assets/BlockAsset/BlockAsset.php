<?php

namespace thefx\blocks\assets\BlockAsset;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class BlockAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/assets';

    public $css = [
        'style.css',
    ];
}
