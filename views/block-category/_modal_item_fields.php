<?php

/* @var $modelFieldsForm BlockFieldsItemForm */

use thefx\blocks\forms\BlockFieldsItemForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$defaultFields = json_encode($modelFieldsForm->getBlock()->getDefaultFieldsTemplates(), JSON_UNESCAPED_UNICODE);
?>

<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal_item_fields"><i class="fa fa-cog mr-1"></i>Материал</button>

<div id="modal_item_fields" class="modal" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content text-sm">
            <div class="modal-header">
                <h5 class="modal-title">Настройка полей</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>

            <?php $form = ActiveForm::begin(); ?>

                <div class="modal-body">
                    <h6 class="text-semibold">По умолчанию</h6>

                    <textarea cols="30" rows="10" class="form-control mb-3" style="resize: vertical" disabled="disabled"><?= $defaultFields ?></textarea>

                    <?= $form->errorSummary($modelFieldsForm, ['class' => 'alert alert-danger']) ?>

                    <h6 class="text-semibold text-left">Шаблон</h6>
                    <?= $form->field($modelFieldsForm, 'textarea')->textarea(['cols' => 30, 'rows' => 10])->label(false) ?>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Закрыть</button>
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>