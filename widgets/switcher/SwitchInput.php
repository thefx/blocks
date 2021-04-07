<?php

namespace thefx\blocks\widgets\switcher;

use yii\widgets\InputWidget;

class SwitchInput extends InputWidget
{
    public function run()
    {
        return $this->render('index', [
            'model' => $this->model,
            'attributeName' => $this->attribute
        ]);
    }
}
