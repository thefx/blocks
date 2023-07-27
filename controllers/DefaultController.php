<?php

namespace thefx\blocks\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->redirect(['/admin/blocks/block-sections/index?block_id=1']);
    }
}
