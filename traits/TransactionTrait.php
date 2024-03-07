<?php

namespace thefx\blocks\traits;

use Yii;

trait TransactionTrait
{
    protected function wrap(callable $function)
    {
        Yii::$app->db->transaction($function);
    }
}