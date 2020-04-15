<?php

namespace thefx\blocks;

use thefx\blocks\assets\BlockAsset\BlockAsset;

class Module extends \yii\base\Module
{
    public $layoutPure = 'pure';
    public $rootUsers = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        BlockAsset::register(\Yii::$app->view);
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            return true;
        }
        return false;
    }
}
