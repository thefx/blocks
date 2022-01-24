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
use thefx\blocks\models\files\Files;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

echo HTML::label($model->prop->title);

$filenames = $model->getFilesArray();
$files = $filenames ? Files::findAll(['file' => $filenames]) : [];
?>

<div class="card card-body bg-light" id="card-<?= $unique ?>">

    <?php if ($files) : ?>

        <table class="mb-3" style="width: 10%">
            <?php foreach ($files as $file) : ?>
                <tr data-key="<?= $file->file ?>">
                    <td class="p-1">
                        <a class="filename" href="<?= $file->getPath() ?>" target="_blank"><?= $file->title ?></a>
                    </td>
                    <td class="p-1">
                        <button class="btn btn-success btn-xs"
                           data-filename="<?= $file->file ?>"
                           data-model-id="<?= $model->id ?>"
                           data-toggle="modal" data-target="#modal_edit_file_info"
                           type="button"
                           title="Редактировать описание">Редактировать&nbsp;описание</button>
                    </td>
                    <td class="p-1">
                        <a class="btn btn-danger btn-xs"
                           href="<?= Url::to(['delete-file-prop', 'name' => $file->file, 'id' => $model->id]) ?>"
                           title="Удалить файл"
                           data-confirm="Удалить файл?"
                           data-method="post">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <?= Html::activeFileInput($model, $attributeName . '[]', [
        'template' => '{input}',
        'multiple' => $model->prop->isMulti(),
        'options' => ['class' => 'form-group no-margin'],
    ]) ?>

</div>
