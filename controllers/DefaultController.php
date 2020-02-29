<?php

namespace thefx\blocks\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->redirect(['/admin/blocks/block-category/index?parent_id=1']);
    }
}
