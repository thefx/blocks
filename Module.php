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
