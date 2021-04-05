<?php

namespace thefx\blocks;

use thefx\blocks\assets\BlockAsset\BlockAsset;

class Module extends \yii\base\Module
{
    public $layoutPure = 'pure';
    public $layout = 'main';
    public $rootUsers = []; // admins

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        BlockAsset::register(\Yii::$app->view);

        // Kartik reset
        \Yii::$app->params['bsDependencyEnabled'] = false; // do not load bootstrap assets for a specific asset bundle
        \Yii::$app->params['bsVersion'] = '4.x';
        \Yii::$app->assetManager->bundles['kartik\select2\ThemeKrajeeBs4Asset'] = [
            'css' => [],
        ];

//        \Yii::$app->assetManager->appendTimestamp = true;
//        \Yii::$app->assetManager->bundles = [
////            'yii\web\JqueryAsset' => [
//////                'js' => [
//////                    '//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js',
//////                ],
////                'jsOptions' => [
////                    'position' =>  View::POS_HEAD,
////                ],
////            ],
////            'yii\bootstrap\BootstrapAsset' => [
////                'css' => [],
////            ],
////            'yii\bootstrap\BootstrapThemeAsset' => [
////                'css' => [],
//////                'css' => [], // ??
////            ],
//
//
////            'yii\bootstrap4\BootstrapAsset' => [
////                'css' => [],
////            ],
////            'yii\bootstrap4\BootstrapPluginAsset' => [
////                'js' => [],
//////                'css' => [], // ??
////            ],
//
//        ];
    }

//    /**
//     * @inheritdoc
//     */
//    public function beforeAction($action)
//    {
//        if (parent::beforeAction($action)) {
//            return true;
//        }
//        return false;
//    }
}
