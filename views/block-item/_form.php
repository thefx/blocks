<?php

use app\shop\entities\Block\Block;
use app\shop\entities\Block\BlockItem;
use app\shop\entities\Block\BlockItemPropAssignments;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model BlockItem */
/* @var $form yii\widgets\ActiveForm */
/* @var $block Block */
/* @var $elem BlockItemPropAssignments[] */

?>

<div class="block-item-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>

        <div class="nav-tabs-custom goods-form">
            <ul class="nav nav-tabs">

                <?php $i = 0 ?>

                <?php foreach ($block->getFieldsTemplates() as $tab => $items) : ?>
                    <?php $class = ($i == 0) ? 'active' : '' ?>
                    <?php $i++ ?>
                    <li class="<?= $class ?>"><a data-toggle="tab" href="#tab_<?= $i ?>"><?= $tab ?></a></li>
                <?php endforeach; ?>

            </ul>
            <div class="tab-content">

                <?php $i = 0 ?>

                <?php foreach ($block->getFieldsTemplates() as $tab => $items) : ?>
                    <?php $class = ($i == 0) ? 'active' : '' ?>
                    <?php $i++ ?>
                    <div id="tab_<?= $i ?>" class="tab-pane <?= $class ?>">
                        <?php foreach ($items as $item) : ?>
                            <?= $this->render('_type_' . $item['type'], ['form' => $form, 'model' => $model, 'block' => $block, 'value' => $item['value']]) ?>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>

            </div>

            <div class="box-footer">
                <div class="form-group">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>
