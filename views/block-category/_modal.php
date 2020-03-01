<?php

/* @var $modelFieldsForm BlockFieldsItemForm */

use thefx\blocks\forms\BlockFieldsItemForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

//$props = ArrayHelper::map(
//    $model->propAssignments,
//    function ($propAssignment) { return $propAssignment->prop->id; },
//    function ($propAssignment) { return $propAssignment->prop->title; }
//);

?>

<?php if (in_array(Yii::$app->user->id, $this->context->module->rootUsers)) : ?>
    <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal_animation"><i class="fa fa-cog"></i></button>
<?php endif; ?>

<div id="modal_animation" class="modal" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h5 class="modal-title">Настройка полей</h5>
            </div>

            <?php $form = ActiveForm::begin(); ?>

            <div class="modal-body">
                <h6 class="text-semibold">По умолчанию</h6>

                <p><textarea cols="30" rows="10" class="form-control" style="resize: vertical" disabled="disabled"><?= json_encode($modelFieldsForm->getBlock()->getDefaultFieldsCategoryTemplates(), JSON_UNESCAPED_UNICODE) ?></textarea></p>

                <hr>

                <?= $form->errorSummary($modelFieldsForm, ['class' => 'alert alert-danger']); ?>

                <h6 class="text-semibold">Шаблон</h6>
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