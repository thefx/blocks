<?php

use thefx\blocks\forms\BlockFieldsForm;
use thefx\blocks\models\blocks\Block;
use thefx\blocks\models\blocks\BlockCategory;
use thefx\blocks\models\blocks\BlockFields;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $block Block */
/* @var $model BlockFieldsForm */
/* @var $category BlockCategory */
/* @var $template string */

switch ($model->block_type) {
    case BlockFields::BLOCK_TYPE_ITEM :
        $titleSuffix = 'элемента';
        break;
    case BlockFields::BLOCK_TYPE_SERIES :
        $titleSuffix = 'серии';
        break;
    case BlockFields::BLOCK_TYPE_CATEGORY :
        $titleSuffix = 'раздела';
        break;
    default:
        die("Unknown block type: {$model->block_type}");
}

$this->title = 'Настройка полей для ' . $titleSuffix;
$this->params['breadcrumbs'][] = ['label' => $block->translate->categories, 'url' => ['block-category/index', 'parent_id' => $category->id]];
$this->params['breadcrumbs'][] = $this->title;

//\app\assets\Codemirror\CodemirrorAsset::register($this);
?>

<div class="block-translate-update">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']) ?>

    <div class="row">
        <div class="col-md-6">
            <h6 class="text-semibold">По умолчанию</h6>
            <textarea id="textarea1" rows="30" class="form-control mb-3" style="resize: vertical" disabled="disabled"><?= $template ?></textarea>
        </div>
        <div class="col-md-6">
            <h6 class="text-semibold text-left">Шаблон</h6>
            <?= $form->field($model, 'template')->textarea(['rows' => 30, 'id' => 'textarea2'])->label(false) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
