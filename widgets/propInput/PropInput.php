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
        PropInputAsset::register($this->view);

//        if ($this->model->prop->isString()) {
//            $view = 'string';
//        } elseif ($this->model->prop->isInteger()) {
//            $view = 'integer';
//        }  elseif ($this->model->prop->isFile()) {
//            $view = 'file';
//        } elseif ($this->model->prop->isList()) {
//            $view = 'file';
//        }

        $view = $this->model->prop->type;

        return $this->render($view, [
            'model' => $this->model,
            'form' => $this->field->form,
//            'form' => $this->field->form,
            'attributeName' => $this->attribute,
        ]);
    }
}
