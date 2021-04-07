<?php

namespace thefx\blocks\assets\Select2Asset;

use yii\web\AssetBundle;

class Select2Asset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte';

    public $css = [
        'plugins/select2/css/select2.min.css',
//        'plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css',
    ];

    public $js = [
        'plugins/select2/js/select2.full.js',
//        'plugins/select2/js/i18n/ru.js',
//        'dist/js/adminlte.js',
    ];
}
