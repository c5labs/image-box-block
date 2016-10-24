(function($){
    "use strict";

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
    }(),

    wireImageSelector = function () {

        // Attach the existing image.
        if (window.image_block_file) {
            attachImage(window.image_block_file);
        }

        // Wire the add image button
        $('#imageHolderSelect').click(function () {
            ConcreteFileManager.launchDialog(function (data) {
                ConcreteFileManager.getFileDetails(data.fID, function(r) {
                    jQuery.fn.dialog.hideLoader();
                    for(var i = 0; i < r.files.length; i++) {
                        if ('Image' === r.files[i].genericTypeText) {
                            getDimensions(r.files[i], function(file, dimensions) {
                                if (dimensions.width < image_block_dimensions.width || dimensions.height < image_block_dimensions.height) {
                                    alert('The image you selected is too small.');
                                    return;
                                }

                                var aspect_ratio = image_block_dimensions.width / image_block_dimensions.height;

                                if (! window.image_block_force_crop || ! file.canEditFile || (dimensions.width / dimensions.height) === aspect_ratio) {
                                    attachImage(file);
                                } else {
                                    cropImage(file);
                                }
                            }, function () {
                                alert('The selected file is invalid.');
                                return;
                            });
                        } else {
                            alert('The file you selected was not an image file.');
                        }
                    }
                });

            }, { 'multipleSelection' : false });
        });

        // Attach an image
        function attachImage(file)
        {
            if (typeof file.resultsThumbnailImg === 'string') {
                var $img = $(file.resultsThumbnailImg.replace('file_manager_listing', 'image_box_image_2x'));
            } else {
                var $img = $('<img></img>');
                $img.prop('src', file.imgData);
            }
            $('#imageHolderWrapper').addClass('selectable');
            $('#imageHolder').empty().append($img);
            $('input[name=fID]').val(file.fID);
            $('#imageHolderSelect span').html('Change Image');
        }

        function getDimensions(file, success, fail)
        {
            $.ajax({
                url: CCM_REL+'/index.php/ccm/system/image-box-block/dimensions/'+file.fID,
                type: 'GET',
                dataType: 'json',
            })
            .done(function(data) {
                success(file, data);
            })
            .fail(function() {
                fail();
            });
        }

        function getFvID(file, success, fail)
        {
            $.ajax({
                url: CCM_REL+'/index.php/ccm/system/image-box-block/current-file-version-resolver/'+file.fID,
                type: 'GET',
                dataType: 'json',
            })
            .done(function(data) {
                success(data);
            })
            .fail(function() {
                fail();
            });
        }

        function cropImage(file)
        {
            if (! file.fvID) {
                file.fvID = getFvID(file, function(fvID) {
                    file.fvID = fvID;
                    cropImage(file);
                });
                return false;
            }

            var d = $.fn.dialog.open({ href: CCM_REL+'/index.php/ccm/system/dialogs/file/thumbnails/edit?fID='+file.fID+'&fvID='+file.fvID+'&thumbnail=image_box_image_2x', width: '90%', height: '70%'});

            Concrete.event.unbind('ImageEditorDidSave.thumbnails');
            Concrete.event.bind('ImageEditorDidSave.thumbnails', function(event, data) {
                if (data.isThumbnail) {
                    attachImage(data);
                    $.fn.dialog.closeTop();
                }
            });
        }
    }();
}(jQuery));