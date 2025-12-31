jQuery(($) => {

    // For settings_page_helperbox
    if (typeof helperboxJS !== 'undefined' && helperboxJS.settings_page_helperbox) {

        var frame;
        const $preview = $('.helperbox_adminlogin_bgimages-preview');
        const $removeAll = $('#helperbox_adminlogin_bgimages_removeAll');

        // Action when add btn is clicked
        $('#helperbox_adminlogin_bgimages_addBtn').on('click', function (e) {
            e.preventDefault();
            mediaFrame(frame, 'Select Login Background Image');
        });

        // Remove all
        $removeAll.on('click', function (e) {
            e.preventDefault();
            $preview.empty();
            $(this).hide();
        });

        // Handle remove button click
        $(document).on('click', '.remove-image', function (e) {
            e.preventDefault();
            var attachmentId = $(this).data('attachment-id');
            $('.selected-image-' + attachmentId).remove();
        });

        // preview attachment
        function previewAttachment(attachment) {
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
                '<input type="hidden" name="helperbox_adminlogin_bgimages[]" value="' + attachmentId + '">' +

                // Remove button
                '<a href="#" class="remove-image button button-secondary button-small" ' +
                'data-attachment-id="' + attachmentId + '" title="Remove image">×</a>' +
                '</div>'
            );
        }


        function mediaFrame(frame, frameTitle, isMultiple = false) {
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
                        $preview.append(previewAttachment(attachment));
                    });
                } else {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $preview.html(previewAttachment(attachment));
                }

                if (isMultiple) {
                    $removeAll.show();
                }
            });

            frame.open();

        }
    }
});


