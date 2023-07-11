<?php

use thefx\blocks\assets\SortableJs\SortableJsAsset;
use thefx\blocks\widgets\Switcher\SwitchInput;
use thefx\blocks\models\BlockProperty;
use thefx\blocks\widgets\Select\Select2Input;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model BlockProperty */
/* @var $form yii\widgets\ActiveForm */

SortableJsAsset::register($this);
?>

<?php Pjax::begin(); ?>

<?php $form = ActiveForm::begin([
//        'enableAjaxValidation' => true,
    'enableClientValidation' => false,
//        'validateOnSubmit'=>true,
//        'options' => ['data-pjax'=>true]
]); ?>

<script>

    // For elements
    document.addEventListener("DOMContentLoaded", function () {

        var index = <?=count($model->elements) -1 ?>;
        var $elem = $('.prop-elem');

        $elem.find('tr').each(function (e) {
            index = index < e ? e : index;
        });

        function addNewElem(target, i)
        {
            $.ajax({
                'type': 'GET',
                'url': '<?= Url::to(['get-elem']) ?>',
                data:{
                    ajax:true,
                    //property_id:<?//=$model->id?>//,
                    index:i,
                },
                dataType: "html"
            }).done(function(data){
                target.find('tbody').append('<tr data-index="' + i + '">' + data + '</tr>');
                index++;
            });
        }

        $elem.on('click', '.btn.add-item', function (e) {
            addNewElem($(e.delegateTarget), index);
        });

        $elem.on('click', '.btn.del-item', function (e) {
            e.preventDefault();
            $(e.target).parents('tr').remove();
        });

        // Sortable

        document.querySelectorAll('.items-sortable').forEach(function (el) {
            Sortable.create(el, {
                draggable: "tr",
                handle: ".handle",
            });
        });

        // Default checkbox
        $('.items-sortable').on('change', '.default_value', function (e) {
            $(e.delegateTarget).find('.default_value').each(function (index, element) {
                if (e.target !== element) {
                    element.checked = false;
                }
            });
        });

    });

</script>

<style>
    .help-block {
        margin: 0;
    }
    .table-prop-items td {
        vertical-align: middle !important;
    }
    .table-prop-items .form-group {
        margin: 0;
    }
</style>

<?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>

<div class="card card-primary card-outline card-outline-tabs">

    <div class="card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="custom-tabs-1-tab" data-toggle="pill" href="#custom-tabs-1" role="tab" aria-controls="custom-tabs-1" aria-selected="true">Краткая информация</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="custom-tabs-2-tab" data-toggle="pill" href="#custom-tabs-2" role="tab" aria-controls="custom-tabs-2" aria-selected="false">Тип: список</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="custom-tabs-3-tab" data-toggle="pill" href="#custom-tabs-3" role="tab" aria-controls="custom-tabs-3" aria-selected="false">Тип: Текст</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="custom-tabs-4-tab" data-toggle="pill" href="#custom-tabs-4" role="tab" aria-controls="custom-tabs-4" aria-selected="false">Тип: Связанный блок</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="custom-tabs-5-tab" data-toggle="pill" href="#custom-tabs-5" role="tab" aria-controls="custom-tabs-5" aria-selected="false">Прочее</a>
            </li>
        </ul>
    </div>

    <div class="card-body">

        <div class="tab-content">
            <div class="tab-pane fade active show" id="custom-tabs-1" role="tabpanel" aria-labelledby="custom-tabs-1-tab">

                <?= $form->field($model, 'public')->widget(SwitchInput::class) ?>

                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'type')->dropDownList($model->getTypes()) ?>

                <?= $form->field($model, 'multiple')->widget(SwitchInput::class) ?>

                <?= $form->field($model, 'required')->widget(SwitchInput::class) ?>

                <?= $form->field($model, 'sort')->textInput() ?>

            </div>
            <div class="tab-pane fade" id="custom-tabs-2" role="tabpanel" aria-labelledby="custom-tabs-2-tab">

                <div class="prop-elem">
                    <table class="table table-prop-items">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Название</th>
                            <th>Код</th>
                            <th class="text-center">Сортировка</th>
                            <th class="text-center">По умолчанию</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="items-sortable">
                        <?php foreach ($model->elements as $index => $element) : ?>
                            <tr data-index="<?= $index ?>">
                                <?= $this->render('_form_elem', [
                                    'index' => $index,
                                    'form' => $form,
                                    'model' => $element,
                                ]) ?>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="btn btn-default add-item">Добавить пункт</div>
                </div>

            </div>
            <div class="tab-pane fade" id="custom-tabs-3" role="tabpanel" aria-labelledby="custom-tabs-3-tab">

                <?= $form->field($model, 'redactor')->widget(SwitchInput::class) ?>

            </div>
            <div class="tab-pane fade" id="custom-tabs-4" role="tabpanel" aria-labelledby="custom-tabs-4-tab">

                <?= $form->field($model, 'relative_item')->widget(Select2Input::class, [
                    'data' => $model->getBlocksList(),
                    'options' => ['placeholder' => 'Значение не выбрано'],
                    'pluginOptions' => [
                        'allowClear' => true,
//                        'prompt' => 'Значение не выбрано'
                    ],
                ]) ?>

            </div>
            <div class="tab-pane fade" id="custom-tabs-5" role="tabpanel" aria-labelledby="custom-tabs-5-tab">

                <?= $form->field($model, 'hint')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'default_value')->dropDownList(['1' => 'Да', '0' => 'Нет'], ['prompt' => '']) ?>

            </div>
        </div>

    </div>

    <div class="card-footer clearfix">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

</div>

<?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>










