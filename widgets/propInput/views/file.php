<?php

/* @var $model BlockItemPropAssignments */
/* @var $form ActiveForm */
/* @var string $mimeTypes */
/* @var string $attributeName */
/* @var string $multiple */
/* @var string $imagesRelativeModel */
/* @var string $delAttributeName */

/* @var string $unique */

use thefx\blocks\models\blocks\BlockItemPropAssignments;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

echo HTML::label($model->prop->title);

?>

<div class="card card-body bg-light " id="card-<?= $unique ?>">

    <?php if ($files = $model->getFilesPath()) : ?>
        <?php foreach ($files as $filename => $path) : ?>

            <div class="mb-3" data-key="<?= $filename ?>">
                <a href="<?= $path ?>" target="_blank"><?= $filename ?></a>
                <a class="btn btn-danger btn-xs"
                   href="<?= Url::to(['delete-file-prop', 'name' => $filename, 'id' => $model->id]) ?>"
                   title="Удалить файл"
                   data-confirm="Удалить файл?"
                   data-method="post">Удалить</a>
            </div>

        <?php endforeach; ?>
    <?php endif; ?>

    <?= Html::activeFileInput($model, $attributeName . '[]', [
        'template' => '{input}',
        'multiple' => $model->prop->isMulti(),
        'options' => ['class' => 'form-group no-margin'],
    ]) ?>

</div>
