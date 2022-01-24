<?php

use thefx\blocks\models\files\Files;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$model = new Files();

?>

<script>

    document.addEventListener("DOMContentLoaded", function() {
        var modal = $('#modal_edit_file_info');

        modal.on('show.bs.modal', function (e) {
            var button = $(e.relatedTarget);
            $.get( "get-file-info", { 'filename': button.data('filename') } ).done(function(data){
                modal.find('form [name="Files[title]"]').val(data.model.title);
                modal.find('form [name="Files[description]"]').val(data.model.description);
                modal.find('form [name="Files[file]"]').val(data.model.file);
            });
        })

        modal.on('submit', 'form', function (e) {
            $.post( "edit-file-info", $(this).serialize() ).done(function(data){
                $('[data-key="' + data.model.file + '"] .filename').text(data.model.title);
                modal.modal('hide');
            });
            e.preventDefault();
        });

        modal.on('hidden.bs.modal', function (e) {
            modal.find('form').trigger("reset");
        })

    });

</script>

<div id="modal_edit_file_info" class="modal" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content text-sm">
            <div class="modal-header">
                <h5 class="modal-title">Описание файла</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>

            <?php $form = ActiveForm::begin(); ?>

                <div class="modal-body">

                    <?= $form->field($model, 'title')->textInput() ?>

                    <?= $form->field($model, 'description')->textarea(['rows' => 5]) ?>

                    <?= $form->field($model, 'file')->hiddenInput()->label(false) ?>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Закрыть</button>
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>