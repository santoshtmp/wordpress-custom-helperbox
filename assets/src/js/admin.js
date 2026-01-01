jQuery(($) => {

    // For settings_page_helperbox
    if (typeof helperboxJS !== 'undefined' && helperboxJS.settings_page_helperbox) {

        var bgImageFrame;
        var logoImageFrame;

        // Action when add btn is clicked
        $('#helperbox_adminlogin_bgimages_addBtn').on('click', function (e) {
            e.preventDefault();
            // get preview area
            const previewSection = $('.helperbox_adminlogin_bgimages-preview');
            const fieldName = $(this).attr('field-name');
            mediaFrame(fieldName, bgImageFrame, 'Select Login Background Image', previewSection, false);
        });

        // Action when add btn is clicked
        $('#helperbox_adminlogin_logo_addBtn').on('click', function (e) {
            e.preventDefault();
            const previewSection = $('.helperbox_adminlogin_logo-preview');
            const fieldName = $(this).attr('field-name');
            mediaFrame(fieldName, logoImageFrame, 'Select Login Form Logo', previewSection, false);
        });

        // Remove all
        $(document).on('click', '.helperbox-delete-all-media', function (e) {
            e.preventDefault();
            const fieldName = $(this).attr('field-name');
            $("." + fieldName + "-preview").empty();
            $(this).hide();
        });

        // Handle remove button click
        $(document).on('click', '.remove-image', function (e) {
            e.preventDefault();
            var attachmentId = $(this).data('attachment-id');
            $('.selected-image-' + attachmentId).remove();
        });

        // preview attachment
        function previewAttachment(fieldName, attachment) {
            if (!fieldName || !attachment) {
                return;
            }
            // Safely escape attributes
            var imgSrc = attachment.url.replace(/&/g, '&amp;').replace(/"/g, '&quot;');
            var attachmentId = parseInt(attachment.id, 10);

            // Unique container for this image
            var containerClass = 'selected-image-' + attachmentId;

            return (
                '<div class="selected-image ' + containerClass + '" >' +
                '<img src="' + imgSrc + '" ' +
                'alt="Selected image">' +

                // Hidden input to store attachment ID (for saving multiple)
                '<input type="hidden" name="' + fieldName + '[]" value="' + attachmentId + '">' +

                // Remove button
                '<a href="#" class="remove-image button button-secondary button-small" ' +
                'data-attachment-id="' + attachmentId + '" title="Remove image">×</a>' +
                '</div>'
            );
        }

        //
        function mediaFrame(fieldName, frame, frameTitle, previewSection, isMultiple = false) {
            // Reuse existing frame if already created
            if (frame) {
                frame.open();
                return;
            }

            // Create the media frame
            frame = wp.media({
                title: frameTitle,
                button: {
                    text: 'Use this image'
                },
                multiple: isMultiple,
                library: {
                    type: 'image'
                }
            });

            // When images are selected
            frame.on('select', function () {
                if (isMultiple) {
                    var attachments = frame.state().get('selection').toJSON();
                    attachments.forEach((attachment) => {
                        previewSection.append(previewAttachment(fieldName, attachment));
                    });
                } else {
                    var attachment = frame.state().get('selection').first().toJSON();
                    previewSection.html(previewAttachment(fieldName, attachment));
                }

                if (isMultiple) {
                    $removeAll.show();
                }
            });

            frame.open();

        }
    }
});


