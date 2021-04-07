<?php

namespace thefx\blocks\widgets\select;

use thefx\blocks\assets\Select2Asset\Select2Asset;
use yii\widgets\InputWidget;

class Select2Input extends InputWidget
{
    public $data;
    public $pluginOptions; // todo

    public function run()
    {
        Select2Asset::register($this->view);

        return $this->render('index', [
            'data' => $this->data,
            'model' => $this->model,
            'attributeName' => $this->attribute
        ]);
    }
}
