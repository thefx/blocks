<?php

use app\widgets\switcher\SwitchInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\shop\entities\Block\BlockProp */
/* @var $elem app\shop\entities\Block\BlockPropElem[] */
/* @var $form yii\widgets\ActiveForm */

\app\assets\Plugins\SortableJs\SortableJsAsset::register($this);
?>


<?php Pjax::begin(); ?>

<?php $form = ActiveForm::begin([
//        'enableAjaxValidation' => true,
    'enableClientValidation'=>false,
//        'validateOnSubmit'=>true,
//        'options' => ['data-pjax'=>true]
]); ?>

<?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>

<div class="nav-tabs-custom block-prop-form">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#tab_main">Общая информация</a></li>
        <li><a data-toggle="tab" href="#tab_type_list">Тип: список</a></li>
        <li><a data-toggle="tab" href="#tab_type_rel">Тип: Связанный блок</a></li>
        <li><a data-toggle="tab" href="#tab_type_file">Тип: Файл</a></li>
        <li><a data-toggle="tab" href="#tab_extra">Прочее</a></li>
    </ul>
    <div class="tab-content">
        <div id="tab_main" class="tab-pane active">

            <?= $form->field($model, 'public')->widget(SwitchInput::class) ?>

            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'type')->dropDownList($model->getTypes()) ?>

            <?= $form->field($model, 'multi')->widget(SwitchInput::class) ?>

            <?= $form->field($model, 'required')->widget(SwitchInput::class) ?>

        </div>
        <div id="tab_type_list" class="tab-pane">

            <script>

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
                                //prop_id:<?//=$model->id?>//,
                                index:i,
                            },
                            dataType: "html"
                        }).done(function(data){
                            target.find('tbody').append('<tr data-index="' + i + '">' + data + '</tr>');
                            index++;
                        });
                    }

                    function delElem(e)
                    {
                        console.log(e);
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
                        e.target.checked = true;

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

            <div class="prop-elem">

                    <table class="table table-prop-items">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Название</th>
                            <th>Код</th>
                            <th class="text-center">Сортировка</th>
                            <th class="text-center">По умолчанию</th>
                        </tr>
                        </thead>
                        <tbody class="items-sortable">
                        <?php foreach ($model->elements as $index => $el) : ?>
                            <tr data-index="<?= $index ?>">
                                <?= $this->render('_form_elem', [
                                    'index' => $index,
                                    'form' => $form,
                                    'model' => $el,
                                ]) ?>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="btn btn-default add-item">Добавить пункт</div>
            </div>

        </div>
        <div id="tab_type_rel" class="tab-pane">

            <?= $form->field($model, 'relative_block_item')->dropDownList($model->getBlocksList(), ['prompt'=>'Значение не выбрано']) ?>

            <?= $form->field($model, 'relative_block_cat')->dropDownList($model->getBlocksList(), ['prompt'=>'Значение не выбрано']) ?>

        </div>
        <div id="tab_type_file" class="tab-pane">

            <?= $form->field($model, 'upload_path')->textInput() ?>

            <?= $form->field($model, 'watermark_path')->textInput() ?>

            <?= $form->field($model, 'web_path')->textInput() ?>

        </div>
        <div id="tab_extra" class="tab-pane">

            <?= $form->field($model, 'hint')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'sort')->textInput() ?>

            <?= $form->field($model, 'in_filter')->widget(SwitchInput::class) ?>

        </div>
        </div>

    <div class="panel-body">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

</div>

<?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>

