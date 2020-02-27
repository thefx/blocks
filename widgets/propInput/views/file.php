<?php

/* @var $model \app\shop\entities\Block\BlockItemPropAssignments */
/* @var $form \yii\widgets\ActiveForm */
/* @var string $mimeTypes */
/* @var string $attributeName */
/* @var string $multiple */
/* @var string $imagesRelativeModel */
/* @var string $delAttributeName */

use yii\helpers\Html;
use yii\helpers\Url;

echo HTML::label($model->prop->title);

$random = uniqid();

?>


<script>
    document.addEventListener("DOMContentLoaded", function () {

        //enableImageGalleryDng('#well-<?//=$random?>// .image-wrapper', <?//=$model->id?>//);
        //
        //$('.well-<?//=$random?>//').on('change', 'input', previewFileWrapper.upload);
    });
</script>

<div class="well well-<?=$random?>" id="well-<?=$random?>">

    <?php

//    echo $form->field($model, $attributeName, ['template' => '{input}', 'options' => ['class' => 'form-group no-margin']])->fileInput(['accept' => $mimeTypes]);

    $path = \Yii::getAlias("@web/upload/{$model->getUploadPath()}") . '/';
    echo '<div class="image-wrapper">';
    if ($images = $model->getImagesPath()) {
        var_dump($images);

        foreach ($images as $image => $path) {
            echo $this->render('_file', [
                'link' => '/upload/blocks/prev_' . $image,
                'data_key' => $image,
                'deleteLink' => Url::to(['delete-file-prop', 'name' => $image, 'id' => $model->id]),
            ]);
        }
    }
    echo '</div>';
    echo '<div class="clearfix"></div>';

    echo Html::activeFileInput($model, $attributeName . '[]', [
        'template' => '{input}',
        'multiple' => $model->prop->isMulti(),
        'options' => ['class' => 'form-group no-margin',/*, 'accept' => 'image/*'*/],
//        "onchange" => "previewFile()"
    ]);

//    if (!$model->{$imagesRelativeModel})  {
////            echo HTML::activeFileInput($model, $attributeName, ['accept' => $mimeTypes]);
//        echo $form->field($model, $attributeName, ['template' => '{input}', 'options' => ['class' => 'form-group no-margin']])->fileInput(['accept' => $mimeTypes]);
//    } else {
//        echo Html::a('<b>' . $model->{$imagesRelativeModel}->title . '</b> ',
//            ['@web/upload/' . $model->{$imagesRelativeModel}->folder . '/' . $model->{$imagesRelativeModel}->filename],
//            ['target' => '_blank']
//        );
//        echo  Html::submitButton('<i class="fa fa-trash"></i> Удалить', [
//            'class' => 'btn btn-danger btn-xs',
//            'data-confirm' => 'Удалить файл?',
//            'name' => $delAttributeName
//        ]);
//    }

    ?>

</div>
