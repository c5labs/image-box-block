(function($, w){
    "use strict";

    w.image_block_editor = {};

    // Attach an image
    w.image_block_editor.setImage = function(file)
    {
        if (typeof file.resultsThumbnailImg === 'string') {
            var handle = $('#imageHolderWrapper').data('thumbnail-type-handle');
            var $img = $(file.resultsThumbnailImg.replace('file_manager_listing', handle));
        } else {
            var $img = $('<img></img>');
            $img.prop('src', file.imgData);
        }
        $('#imageHolderWrapper').addClass('selectable');
        $('#imageHolder').empty().append($img);
        $('input[name=fID]').val(file.fID);
        $('#imageHolderSelect span').html('Change Image');
        $('#cropBtn').data('fid', file.fID);
    };

    var wirePageSelector = function () {
        $('select[name=link_type]').change(function(){
            if ('page_selector' === $(this).val()) {
                $('#pageSelector, #buttonText').show();
                $('#manualLink').hide();
            } else if ('manual' === $(this).val()) {
                $('#pageSelector').hide();
                $('#manualLink, #buttonText').show();
            } else {
                $('#pageSelector, #manualLink, #buttonText').hide();
            }
        }).trigger('change');
    },

    wireImageSelector = function () {

        // Wire the add image button
        $('#imageHolderSelect').click(function () {
            ConcreteFileManager.launchDialog(function (data) {
                ConcreteFileManager.getFileDetails(data.fID, function(r) {
                    jQuery.fn.dialog.hideLoader();
                    for(var i = 0; i < r.files.length; i++) {
                        if ('Image' === r.files[i].genericTypeText) {
                            //if (! w.image_block_editor.crop_prompt || ! r.files[i].canEditFile || ! confirm('Do you want to adjust the cropping of this image?')) {
                                w.image_block_editor.setImage(r.files[i]);
                            //} else {
                              //  cropImage(r.files[i]);
                            //}
                        } else {    
                            alert('The file you selected was not an image file.');
                        }
                    }
                });

            }, { 'multipleSelection' : false });
        });

        // Wire th crop button.
         $('#cropBtn').click(function() {
            cropImage({ fID: $(this).data('fid')});
         })

        function cropImage(file)
        {
            var handle = $('#imageHolderWrapper').data('thumbnail-type-handle');

            var d = $.fn.dialog.open({ 
                href: CCM_REL+'/index.php/ccm/system/dialogs/file/thumbnails/edit?fID='+file.fID+'&thumbnail='+handle+'_2x', 
                width: '90%', height: '70%'
            });

            Concrete.event.unbind('ImageEditorDidSave.thumbnails');
            Concrete.event.bind('ImageEditorDidSave.thumbnails', function(event, data) {
                if (data.isThumbnail) {
                    w.image_block_editor.setImage(data);
                    $.fn.dialog.closeTop();
                }
            });
        }
    };

    $(function() {
        wirePageSelector();
        wireImageSelector();
    });
}(jQuery, window));