<?php

/* @var $modelFieldsForm BlockFieldsItemForm */

use thefx\blocks\forms\BlockFieldsItemForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="mb-3">
    <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal_animation"><i class="fa fa-cog"></i></button>
</div>

<div id="modal_animation" class="modal" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Настройка полей</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>

            <?php $form = ActiveForm::begin(); ?>

            <div class="modal-body">
                <h6 class="text-semibold text-left">По умолчанию</h6>
                <p><textarea cols="30" rows="10" class="form-control" style="resize: vertical" disabled="disabled"><?= json_encode($modelFieldsForm->getBlock()->getDefaultFieldsCategoryTemplates(), JSON_UNESCAPED_UNICODE) ?></textarea></p>

                <?= $form->errorSummary($modelFieldsForm, ['class' => 'alert alert-danger']); ?>

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