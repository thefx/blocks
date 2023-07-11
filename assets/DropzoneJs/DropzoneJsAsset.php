<?php
namespace thefx\blocks\assets\DropzoneJs;

use Yii;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;
use yii\web\View;

/**
 * Dropzone is a simple JavaScript library that helps you
 * add file drag and drop functionality to your web forms.
 * It is one of the most popular drag and drop library
 * on the web and is used by millions of people.
 *
 * https://docs.dropzone.dev/misc/tips
 */
class DropzoneJsAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/assets';

    public $js = [
        'dropzone.min.js',
    ];

    public $css = [
        'dropzone.min.css',
    ];

    public $depends = [
        JqueryAsset::class
    ];

    public function init()
    {
        Yii::$app->view->registerJs('
            Dropzone.autoDiscover = false;
        ', View::POS_END);

        parent::init();
    }
}