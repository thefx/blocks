<?php
/** @var $block Block */

//$this->registerJs(" $('#{$inputId}').select2();", \yii\web\View::POS_READY);
use thefx\blocks\assets\Select2Asset\Select2Asset;
use thefx\blocks\models\Block;

Select2Asset::register($this);

$sectionList = $block->getSectionList('.');
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
            if ($(this).val() === 'move') {
                $('#treeCategoryWrapper').removeClass('d-none')
            } else {
                $('#treeCategoryWrapper').addClass('d-none')
            }
        })

        $('#treeCategory').select2();
        $('#treeCategory').parent().addClass('d-none');

        $('#formOptions').on('click', '[type="button"]', function () {
            var button = $(this);
            var form = button.parents('form');
            var formData = form.serializeArray();
            var keys = $('#tableTree').yiiGridView('getSelectedRows');
            var taskName = $('#formOptions [name="task"]').val();

            keys.map(function (key) {
                formData.push({name:'keys[]',value:key})
            });

            $.get('<?= \yii\helpers\Url::to(['options-task-']) ?>' + taskName, formData, function(data){
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
                    <?php if ($sectionList) : ?>
                    <option value="move">Переместить</option>
                    <?php endif; ?>
                    <option value="delete">Удалить</option>
                </select>
                <?php if ($sectionList) : ?>
                <div id="treeCategoryWrapper" class="mr-sm-2">
                    <label class="sr-only" for="treeCategory">Category</label>
                    <select name="sectionId" id="treeCategory" class="form-control mr-sm-2">
                        <?php foreach ($sectionList as $catId => $catName) : ?>
                            <option value="<?= $catId ?>"><?= $catName ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
                <button type="button" class="btn btn-primary btn-sm ml-0">Отправить</button>
            </form>
        </td>
    </tr>
</table>
{pager}