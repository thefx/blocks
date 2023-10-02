<?php

namespace thefx\blocks\controllers;

use thefx\blocks\models\Block;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
//        $block = Block::find()->limit(1)->orderBy('sort')->one();
//        return $this->redirect(['/admin/blocks/block-sections/index?block_id=1']);
    }
}
