<?php

namespace thefx\blocks\widgets\PropertyInput;

use thefx\blocks\models\BlockItemPropertyAssignments;
use yii\widgets\InputWidget;

class PropertyInputWidget extends InputWidget
{
    /**
     * @var BlockItemPropertyAssignments
     */
    public $model;

    public $type;

    public $label;

    public $debug = false;

    /**
     * @return string
     */
    public function run()
    {
        $view = $this->type;

        if (!file_exists(__DIR__ . '/views/' . $view . '.php')) {
            return 'views/' . $view . ' not found';
        }

        return $this->render($view, [
            'model' => $this->model,
            'form' => $this->field->form,
            'attributeName' => $this->attribute,
            'label' => $this->label,
//            'unique' => $this->id,
            'debug' => $this->debug,
        ]);
    }
}
