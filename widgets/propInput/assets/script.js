var previewFileWrapper = {

    wrapper: this,
    $imageWrapper: '',

    getTemplate: function(src, deleteLink, newClass)
    {
        var deleteBtn =
            '<a class="btn-delete"' +
                ' href="' + deleteLink + '"' +
                ' title="Удалить изображение"' +
                ' data-confirm="Удалить изображение?"' +
                ' data-method="post">' +
                '<i class="fa fa-times-circle"></i>' +
            '</a>';

        return $('<div class="mb-10 mr-10 thumbnail pull-left ' + newClass + '" style="">' +
            (newClass !== '' ? '' : deleteBtn) +
            '<div class="image">' +
                '<img src="' + src + '" alt="">' +
            '</div>' +
            '<div class="caption"></div>' +
            '</div>');
    },

    run: function(e)
    {
        previewFileWrapper.$imageWrapper = $(e.delegateTarget);

        if (!previewFileWrapper.isInputMultiple(this)) {
            previewFileWrapper.$imageWrapper.find('.image-wrapper').html('');
        }
        $(this.files).each(function(index, file){
            previewFileWrapper.fillImage(file);
        });
    },

    upload: function(e)
    {
        previewFileWrapper.$imageWrapper = $(e.delegateTarget);

        if (!previewFileWrapper.isInputMultiple(this)) {
            previewFileWrapper.$imageWrapper.find('.image-wrapper').html('');
        }
        previewFileWrapper.$imageWrapper.find('.thumbnail-new').remove();

        $(this.files).each(function(index, file){
            previewFileWrapper.fillImage(file);
        });
        // $(e.currentTarget).remove();
        //
        // console.log(e);
    },

    isInputMultiple: function(elem)
    {
        return $(elem).attr('multiple') === 'multiple';
    },

    fillImage: function(file)
    {
        var reader  = new FileReader();

        reader.addEventListener("load", function () {
            var thumbnail = previewFileWrapper.getTemplate(reader.result, '', 'thumbnail-new');
            // console.log(thumbnail);
            previewFileWrapper.$imageWrapper.find('.image-wrapper').append(thumbnail);
        }, false);

        if (file) {
            reader.readAsDataURL(file);
        }
    },
};

// (function ($) {
//     'use strict';
//
//     $.fn.ImagesPreview = function (settings) {
//         var $wrapper = this;
//
//         var tmpl = '<div class="editable-input2">' +
//             '<span class="my-editable-text">(введите текст нарушения здесь)</span>' +
//             '<textarea class="my-editable-input form-control hide" name="text" style="width:100%"></textarea>' +
//             '</div>' +
//             '<div class="my-editable-buttons mt-5">' +
//             '<span class="btn btn-primary btn-submit hide"><i class="icon-checkmark2"></i></span> ' +
//             '<span class="btn btn-default btn-cancel hide"><i class="icon-cross2"></i></span>' +
//             '</div>';
//
//         var tmpl2 = '<div class="mb-10 mr-10 thumbnail pull-left">' +
//             '<a class="btn-delete" href="/admin/block-item/delete-photo?field=value&amp;id=38" title="Удалить изображение" data-confirm="Удалить изображение?" data-method="post">' +
//             '<i class="fa fa-times-circle"></i>' +
//             '</a>' +
//             '<div class="image">' +
//             '<img src="/upload/blocks/2019_02_16_2006285c68431478162.jpg" alt="">' +
//             '</div>' +
//             '<div class="caption"> </div>' +
//             '</div>';
//
//         var options = $.extend({
//             value : '',
//             emptyText : '(введите текст нарушения здесь)',
//             extra : {},
//             fieldText : '.my-editable-text',
//             fieldInput : '.my-editable-input',
//             btnSubmit : '.btn-submit',
//             btnCancel : '.btn-cancel',
//             url : '/results/add-text',
//             tmpl : tmpl2
//         }, settings);
//
//         $wrapper.append(options.tmpl);
//
//
//
//
//
//
//         var $fieldText = $wrapper.find(options.fieldText);
//         var $input = $wrapper.find(options.fieldInput);
//         var $btnSubmit = $wrapper.find(options.btnSubmit);
//         var $btnCancel = $wrapper.find(options.btnCancel);
//
//         $fieldText.text(options.value || options.emptyText);
//         $input.text(options.value);
//
//         var originalText = $input.val();
//
//         $fieldText.on('click', edit);
//         $btnSubmit.on('click', submit);
//         $input.on('keydown', keypress);
//         $btnCancel.on('click', cancel);
//         $wrapper.on('check.text.add', function () {
//             loading = true;
//             setTimeout(function () {
//                 if (loading) {
//                     $wrapper.addClass('loadings');
//                 }
//             }, 800);
//         });
//         $wrapper.on('check.text.added', function (e) {
//             loading = false;
//             $wrapper.removeClass('loadings');
//         });
//
//         function edit(e) {
//             originalText = $input.val();
//             $fieldText.addClass('hide');
//             $input.removeClass('hide');
//
//             $input.focus();
//             $input.val('');
//             $input.val(originalText);
//
//             $btnSubmit.removeClass('hide');
//             $btnCancel.removeClass('hide');
//         }
//
//         function keypress(e) {
//             if (e.keyCode === 13) {  // Enter
//                 e.preventDefault();
//                 submit();
//             }
//             if (e.keyCode === 27) {  // Esc
//                 e.preventDefault();
//                 cancel();
//             }
//         }
//
//         function submit(e) {
//             var newValue = $input.val();
//
//             //var id = $wrapper.data('id');
//             //var attribute = $wrapper.data('attribute');
//
//             $wrapper.find(options.fieldText).text(newValue || options.emptyText);
//             $fieldText.removeClass('hide');
//             $input.addClass('hide');
//             $btnSubmit.addClass('hide');
//             $btnCancel.addClass('hide');
//
//             if (newValue === originalText) {
//                 return;
//             }
//
//             var data = {
//                 'value':newValue,
//                 'name':options.extra.itemId,
//                 'checkId':options.extra.checkId,
//                 'groupId':options.extra.groupId
//             };
//
//             //console.log([data, options.url, $wrapper]);
//             sendPost(data, function (data) {
//                 originalText = newValue;
//                 console.log(data);
//             });
//         }
//
//         function cancel(e) {
//             $input.val(originalText);
//
//             $fieldText.removeClass('hide');
//             $input.addClass('hide');
//             $btnSubmit.addClass('hide');
//             $btnCancel.addClass('hide');
//         }
//
//         function sendPost(data, successCallback) {
//             $wrapper.trigger("check.text.add");
//
//             data._csrf = yii.getCsrfToken();
//
//             $.ajax({
//                 type: 'POST',
//                 url: options.url,
//                 data: data,
//                 dataType: 'json'
//             }).done(function (data) {
//                 if (typeof successCallback === 'function') {
//                     successCallback(data);
//                 }
//                 $wrapper.trigger("check.text.added");
//             }).fail(function () {
//                 //console.log('fail');
//                 cancel();
//             }).always(function (data) {});
//         }
//     };
//
// })(jQuery);

// $('.add-text-wrapper-{$itemId}').CheckAddText({
//     value: '{$value}',
//     emptyText: '{$emptyText}',
//     extra: {
//         checkId: {$model->id},
// itemId: {$itemId}
// },
// });

//$( document ).ready(function() {
//
//    $('.well.well-<?//=$random?>//').ImagesPreview({
//
//    });
//
//
//    var previewFile = function(e) {
//        console.log(this);
//        console.log(e);
//        console.log('previewFile');
//        var preview = document.querySelector(".file_upload_preview");
//        var file    = document.querySelector("input[type=file]").files[0];
//        var reader  = new FileReader();
//
//        reader.addEventListener("load", function () {
//            preview.src = reader.result;
//        }, false);
//
//        if (file) {
//            reader.readAsDataURL(file);
//        }
//    }
//
//});
