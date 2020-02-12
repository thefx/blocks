<?php

namespace thefx\blocks;

/**
 * This is just an example.
 */
class AutoloadExample extends \yii\base\Widget
{
    public function run()
    {
        return "Hello222!";
    }

//php yii migrate --migrationPath=@thefx/blocks/migrations
//php yii migrate --migrationPath=@app/extensions/thefx/yii2-blocks

}
