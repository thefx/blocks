<?php
/** @var $block \thefx\blocks\models\blocks\Block */

//$this->registerJs(" $('#{$inputId}').select2();", \yii\web\View::POS_READY);
\thefx\blocks\assets\Select2Asset\Select2Asset::register($this);
?>
{summary}
{items}
<style>
    #tableTree table:not(.table-option-tasks) {
        margin-bottom: 0;
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        function disableOptions() {
            $('#formOptions select').attr('disabled', true);
            $('#formOptions button').attr('disabled', true);
        }

        function enableOptions() {
            $('#formOptions select').attr('disabled', false);
            $('#formOptions button').attr('disabled', false);
        }

        disableOptions();

        $('#tableTree').on('change', '[type="checkbox"]', function () {
            var keys = $('#tableTree').yiiGridView('getSelectedRows');
            if (keys.length > 0) {
                enableOptions();
            } else {
                disableOptions();
            }
        })

        $('#treeCategoryTask').on('change', function () {
            $('#treeCategoryWrapper').addClass('d-none');
            $('#treeSeriesWrapper').addClass('d-none');

            if ($(this).val() === 'move') {
                $('#treeCategoryWrapper').removeClass('d-none')
            }
            if ($(this).val() === 'move-series') {
                $('#treeSeriesWrapper').removeClass('d-none')
            }
        })

        $('#treeCategory').select2();
        $('#treeCategory').parent().addClass('d-none');

        $('#selectSeries').select2();
        $('#selectSeries').parent().addClass('d-none');

        $('#formOptions').on('click', '[type="button"]', function () {
            let button = $(this);
            let form = button.parents('form');
            let formData = form.serializeArray();
            // var keys = $('#tableTree').yiiGridView('getSelectedRows');
            let taskName = $('#formOptions [name="task"]').val();
            let $grid = $('#tableTree');

            $grid.find("input[name='selection[]']:checked").each(function () {
                formData.push({
                    name:$(this).parent().closest('tr').data('type')+'[]',
                    value:$(this).parent().closest('tr').data('key')
                })
            });

            $.get('<?= \yii\helpers\Url::to(['block-category-tasks/index']) ?>/' + taskName, formData, function(data){
                if (data.result === 'success') {
                    document.location.reload();
                    // $.pjax.reload({container: '#table'});
                    // $light.unblock();
                }
            }, 'json');
        });
    });
</script>
<table class="table table-bordered table-option-tasks">
    <tr>
        <td>
            <form class="form-inline pl-1" id="formOptions">
                <label class="sr-only" for="treeCategoryTask">Task</label>
                <select name="task" id="treeCategoryTask" class="form-control mr-sm-2">
                    <option value="activate">Активировать</option>
                    <option value="deactivate">Деактивировать</option>
                    <option value="move">Переместить</option>
                    <option value="move-series">Переместить в серию</option>
                    <option value="delete">Удалить</option>
                </select>
                <div id="treeCategoryWrapper" class="mr-sm-2">
                    <label class="sr-only" for="treeCategory">Category</label>
                    <select name="categoryId" id="treeCategory" class="form-control mr-sm-2">
                        <?php foreach ($block->categoryList('.') as $catId => $catName) : ?>
                            <option value="<?= $catId ?>"><?= $catName ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div id="treeSeriesWrapper" class="mr-sm-2">
                    <label class="sr-only" for="treeSeriesWrapper">Series</label>
                    <select name="seriesId" id="selectSeries" class="form-control mr-sm-2">
                        <?php foreach ($block->seriesList() as $id => $title) : ?>
                            <option value="<?= $id ?>"><?= $title ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="button" class="btn btn-primary ml-0">Отправить</button>
            </form>
        </td>
    </tr>
</table>
{pager}