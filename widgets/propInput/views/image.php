<?php

/* @var $model BlockItemPropAssignments */
/* @var $form ActiveForm */
/* @var string $mimeTypes */
/* @var string $attributeName */
/* @var string $multiple */
/* @var string $imagesRelativeModel */
/* @var string $delAttributeName */

use thefx\blocks\models\blocks\BlockItemPropAssignments;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

echo HTML::label($model->prop->title);

$random = uniqid();

?>


<script>
    document.addEventListener("DOMContentLoaded", function () {

        enableImageGalleryDng('#well-<?= $random ?> .image-wrapper', <?= $model->id ?>);

        $('.well-<?= $random ?>').on('change', 'input', previewFileWrapper.upload);
    });
</script>

<div class="well well-<?= $random ?>" id="well-<?= $random ?>">

    <?php

    $path = \Yii::getAlias("@web/upload/{$model->getUploadPath()}") . '/';

    echo '<div class="image-wrapper">';
    if ($images = $model->getImagesPath()) {
        foreach ($images as $image => $path) {
            echo $this->render('_image', [
                'link' => '/upload/blocks/prev_' . $image,
                'data_key' => $image,
                'deleteLink' => Url::to(['delete-photo-prop', 'name' => $image, 'id' => $model->id]),
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

    ?>

</div>
