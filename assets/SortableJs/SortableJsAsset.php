<?php
namespace thefx\blocks\assets\SortableJs;

use Yii;
use yii\web\AssetBundle;

class SortableJsAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/assets';

    public $js = [
        'Sortable.min.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function init()
    {
        Yii::$app->view->registerCss('
            .sortable-chosen {outline: 1px dashed #3c8dbc}
        ');

        /* @see https://github.com/RubaXa/Sortable */
//        Yii::$app->view->registerJs('
//
//            var el = document.querySelector("#categories-dng tbody");
////            var el = document.getElementById("categories-dng");
////            var sortable = Sortable.create(el);
//
//            var sortable = new Sortable(el, {
//                draggable: "tr",  // Specifies which items inside the element should be draggable
//                dataIdAttr: "data-key",
//
//                // Changed sorting within list
//                onUpdate: function (/**Event*/evt) {
//                    el.querySelectorAll("tr").forEach(function(item, i, arr) {
//                      console.log($(item).data("key"));
//                    });
//                },
//            });
//
//        ', View::POS_READY);

        parent::init();
    }


}