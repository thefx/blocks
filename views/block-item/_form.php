<?php

use thefx\blocks\assets\Select2Asset\Select2Asset;
use thefx\blocks\forms\BlockItemCompositeForm;
use thefx\blocks\models\blocks\Block;
use thefx\blocks\models\blocks\BlockItem;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model BlockItem */
/* @var $form yii\widgets\ActiveForm */
/* @var $block Block */
/* @var $template array */
/* @var $propsCompareTemplate array */
/* @var $blockItemCompositeForm BlockItemCompositeForm */

Select2Asset::register($this);
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<?= $form->errorSummary($model, ['class' => 'alert alert-danger']) ?>


<div class="card card-primary card-outline card-outline-tabs">

    <?php if (count($template) > 1) : ?>
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" role="tablist">

                <?php $i = 0 ?>

                <?php foreach ($template as $tab => $items) : ?>
                    <?php $selected = ($i === 0) ? 'true' : 'false' ?>
                    <?php $class = ($i === 0) ? 'active' : '' ?>
                    <?php $i++ ?>

                    <li class="nav-item">
                        <a class="nav-link <?= $class ?>"
                           id="custom-tabs-<?= $i ?>-tab"
                           data-toggle="pill"
                           href="#custom-tabs-<?= $i ?>" role="tab"
                           aria-controls="custom-tabs-<?= $i ?>"
                           aria-selected="<?= $selected ?>"><?= $tab ?></a>
                    </li>

                <?php endforeach; ?>

                <?php if ($model->isSeries()) : ?>
                    <li class="nav-item">
                        <a class="nav-link"
                           id="custom-tabs-10-tab"
                           data-toggle="pill"
                           href="#custom-tabs-100" role="tab"
                           aria-controls="custom-tabs-100"
                           aria-selected="false">Для таблицы</a>
                    </li>
                <?php endif; ?>

                <?php if ($model->isItem()) : ?>
                    <li class="nav-item">
                        <a class="nav-link"
                           id="custom-tabs-10-tab"
                           data-toggle="pill"
                           href="#custom-tabs-100" role="tab"
                           aria-controls="custom-tabs-100"
                           aria-selected="false">Свойства для таблицы</a>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    <?php endif; ?>

    <div class="card-body">
        <div class="tab-content">

            <?php $i = 0 ?>

            <?php foreach ($template as $tab => $items) : ?>
                <?php $class = ($i === 0) ? 'active show' : '' ?>
                <?php $i++ ?>

                <div class="tab-pane fade <?= $class ?>" id="custom-tabs-<?= $i ?>" role="tabpanel" aria-labelledby="custom-tabs-<?= $i ?>-tab">
                    <?php foreach ($items as $item) : ?>
                        <?= $this->render('_type_' . $item['type'], ['form' => $form, 'model' => $model, 'label' => $item['name'], 'block' => $block, 'value' => $item['value']]) ?>
                    <?php endforeach; ?>
                </div>

            <?php endforeach; ?>

            <?php if ($model->isSeries()) : ?>
                <div class="tab-pane fade" id="custom-tabs-100" role="tabpanel" aria-labelledby="custom-tabs-100-tab">
                    <?= $form->field($blockItemCompositeForm, 'propCompareIds')->checkboxList($blockItemCompositeForm->getPropsCompareList(), ['separator' => '<br />'])->label('Какие характеристики будут в таблице') ?>
                </div>
            <?php endif; ?>
            <?php if ($model->isItem()) : ?>
                <div class="tab-pane fade" id="custom-tabs-100" role="tabpanel" aria-labelledby="custom-tabs-100-tab">
                    <?php foreach ($propsCompareTemplate as $item) : ?>
                        <?= $this->render('_type_' . $item['type'], ['form' => $form, 'model' => $model, 'label' => $item['name'], 'block' => $block, 'value' => $item['value']]) ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card-footer clearfix">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

</div>
<?php ActiveForm::end(); ?>

