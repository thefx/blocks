<?php

namespace thefx\blocks\services;

use Yii;

class TransactionManager
{
    public function wrap(callable $function)
    {
        Yii::$app->db->transaction($function);
    }
}