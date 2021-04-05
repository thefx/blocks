<?php

namespace thefx\blocks\widgets\propInput;

use thefx\blocks\models\blocks\BlockItemPropAssignments;
use yii\widgets\InputWidget;

class PropInput extends InputWidget
{
    /**
     * @var BlockItemPropAssignments
     */
    public $model;

    /**
     * @return string
     */
    public function run()
    {
        \thefx\blocks\assets\PropInputAsset\PropInputAsset::register($this->view);

        $view = $this->model->prop->type;

        return $this->render($view, [
            'model' => $this->model,
            'form' => $this->field->form,
            'attributeName' => $this->attribute,
            'unique' => $this->id,
        ]);
    }
}
