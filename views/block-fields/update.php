<?php

use thefx\blocks\models\Block;
use thefx\blocks\models\BlockFields;
use thefx\blocks\models\forms\BlockFieldsForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $block Block */
/* @var $model BlockFieldsForm */
/* @var $template string */

$this->title = 'Настройка полей для ' . ($model->block_type === BlockFields::TYPE_BLOCK_ITEM ? 'элемента' : 'раздела');
$this->params['breadcrumbs'][] = ['label' => $block->translate->blocks_item, 'url' => ['block-sections/index', 'block_id' => $block->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-translate-update">

    <div class="block-translate-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->errorSummary($model, ['class' => 'alert alert-danger']) ?>

        <div class="row">
            <div class="col-md-6">
                <h6 class="text-semibold">По умолчанию</h6>
                <textarea rows="30" class="form-control mb-3" style="resize: vertical" disabled="disabled"><?= $template ?></textarea>
            </div>
            <div class="col-md-6">
                <h6 class="text-semibold text-left">Шаблон</h6>
                <?= $form->field($model, 'template')->textarea(['rows' => 30])->label(false) ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
