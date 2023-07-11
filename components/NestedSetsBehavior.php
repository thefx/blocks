<?php

namespace thefx\blocks\components;

class NestedSetsBehavior extends \paulzi\nestedsets\NestedSetsBehavior
{
    public function isRoot()
    {
        return false;
    }
}
