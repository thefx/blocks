<?php

/* @var $form ActiveForm */
/* @var string $widgetId */
/* @var array $photos */
/* @var Model $model */
/* @var string $mimeTypes */
/* @var string $attributeName */
/* @var string $multiple */
/* @var string $imagesRelativeModel */
/* @var string $delAttributeName */
/* @var string $acceptedFiles */
/* @var string $resizeWidth */
/* @var string $resizeHeight */
/* @var string $maxFiles */
/* @var string $uploadUrl */
/* @var array $extraData */

use yii\base\Model;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->registerCss("
    .dropzone {
        padding: 3px 3px;
        border: 1px solid rgb(204 204 204);
        border-radius: 3px;
        min-height: auto;
    }
    .dropzone .dz-preview {
        margin: 3px;
    }
    .dropzone .dz-preview .dz-image {
        border-radius: 3px;
    }
    .dropzone .dz-preview .dz-image img {
        border-radius: 3px;
        cursor: pointer;
    }
    .dropzone .dz-preview .dz-image {
        width: 155px;
        height: 155px;
    }
    .dropzone .dz-preview .dz-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    /*.dropzone.dz-started .dz-message {*/
    /*    display: block;*/
    /*}*/
    .dropzone .dz-preview .dz-image img,
    .dropzone .dz-preview .dz-details {
        cursor: move;
    }
    .dropzone .dz-preview.dz-image-preview {
        /*border: 1px solid #ccc;*/
        border-radius: 3px;
        overflow: hidden;
    }
    .dropzone .dz-preview:hover .dz-image img {
        transform: none;
        filter: blur(2px);
    }
    .dropzone .dz-preview .dz-error-message {
        /*display: block;*/
        opacity: 1;
        bottom: 10px;
        left: 7px;
        top: auto;
    }
    .dropzone.dz-clickable button {
        cursor: pointer;
    }
", [], 'dropzone');
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        var $listWrapper = $('#<?= $widgetId ?>-dz');
        var inputElement = $('#<?= $widgetId ?>');

        var dataInit = <?= json_encode($photos, JSON_UNESCAPED_UNICODE) ?>;

        function dropzoneAddFile(inputElement, value) {
            var imagesNameArray = inputElement.val().split(';');
            imagesNameArray.push(value);
            var resultArray = imagesNameArray.filter((n) => {return n != ''});
            inputElement.val(resultArray.join(';'));
        }

        function dropzoneRemoveFile(inputElement, value) {
            var imagesNameArray = inputElement.val().split(';');
            var resultArray = imagesNameArray.filter((n) => {return n != value});
            inputElement.val(resultArray.join(';'));
        }

        function getNames() {
            var nameArray = [];
            $listWrapper.get(0).querySelectorAll(".dz-preview").forEach(function(item, i, arr) {
                nameArray.push($(item).data("id"));
            });
            return nameArray;
        }

        var sortable = new Sortable($listWrapper.get(0), {
            draggable: ".dz-preview",  // Specifies which items inside the element should be draggable
            dataIdAttr: "data-id",
            // ignore: ".dz-details",
            // filter: "button",
            onUpdate: function (/**Event*/evt) {
                inputElement.val(getNames().join(';'));
            },
        });

        var dropzone = new Dropzone($listWrapper.get(0), {
            previewTemplate: document.querySelector('#previews').innerHTML,
            url: '<?= $uploadUrl ?>',
            acceptedFiles: '<?= $acceptedFiles ?>',
            resizeWidth: '<?= $resizeWidth ?>',
            resizeHeight: '<?= $resizeHeight ?>',
            paramName: 'file',
            resizeQuality: 1,
            thumbnailWidth: 180,
            thumbnailHeight: 180,
            // maxFiles: <?php //= $maxFiles ?>//,
            // maxFilesize: 2, // MB
            // parallelUploads: 1,
            // resizeMimeType: null,
            // resizeMethod: "contain",
            // filesizeBase: 1e3,
            // maxFiles: null,
            params: {
                _csrf: yii.getCsrfToken(),
            },
            init: function() {
                var myDropzone = this;

                // reset
                inputElement.val('');

                this.on("addedfile", function(file, element) {
                    var _this = this;
                    var button = file.previewTemplate.querySelector('button');

                    // data attribute for sort
                    file.previewTemplate.dataset.id = file.id;

                    // delete button
                    button.addEventListener("click", function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        _this.removeFile(file);
                        dropzoneRemoveFile(inputElement, file.id)
                    });
                });

                this.on("success", function(file, result) {
                    file.id = result.id;
                    file.previewTemplate.dataset.id = file.id;
                    // error message on error
                    if (file.id === undefined) {
                        file.previewTemplate.classList.add("dz-error");
                        file.previewTemplate.querySelector('.dz-error-message').innerHTML = 'Ошибка...';
                    }
                });

                this.on("processing", function(file, element) {
                    sortable.option('sort', 0);
                });

                this.on("complete", function(file) {
                    dropzoneAddFile(inputElement, file.id);
                    sortable.option('sort', 1);
                });

                dataInit.forEach(function (element) {
                    var mockFile = {
                        status: "success",
                        id: element.id,
                        name:  element.file_name,
                        file_name:  element.path + element.file_name,
                        size: element.size,
                    }
                    myDropzone.files.push(mockFile);    // add to files array
                    myDropzone.emit("addedfile", mockFile);
                    myDropzone.emit("thumbnail", mockFile, element.path + element.file_name);
                    myDropzone.emit("complete", mockFile);
                });
            }
        });

        dropzone.on("sending", function(file, xhr, formData) {
            <?php foreach ($extraData as $key => $extraDatum) :?>
            formData.append("<?= $key ?>", <?= $extraDatum ?>);
            <?php endforeach;?>
        });
    });
</script>

<div class="table table-striped files hide d-none" id="previews">
    <div class="dz-preview dz-image-preview">
        <div class="dz-image"><img data-dz-thumbnail /></div>
        <div class="dz-details">
            <!--            <div class="dz-size"><span data-dz-size><strong>0</strong> b</span></div>-->
            <!--            <div class="dz-filename"><span data-dz-nam></span></div>-->
            <!--            <div class="dz-filename2"></div>-->
            <button>Удалить</button>
        </div>
        <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
        <div class="dz-error-message"><span data-dz-errormessage></span></div>
        <div class="dz-success-mark"></div>
        <div class="dz-error-mark"></div>
    </div>
</div>

<div class="dropzone dz-clickable" id="<?= $widgetId ?>-dz">
    <div class="dz-default dz-message">
        <span><i class="sl sl-icon-plus"></i> Нажмите здесь или перетащите файлы для загрузки</span>
    </div>
</div>

<?=  Html::activeHiddenInput($model, $attributeName, ['class' => 'form-control', 'id' => $widgetId]) ?>
