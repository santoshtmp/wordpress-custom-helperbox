jQuery(($) => {

    // For settings_page_helperbox
    if (helperboxAjax && helperboxAjax.hook == 'settings_page_helperbox') {

        // https://rudrastyh.com/wordpress/customizable-media-uploader.html
        // Background Images Uploader
        let mediaFrame;

        $(document).on('click', '.helperbox-add-bg-images', function (e) {
            e.preventDefault();
            // Reuse existing frame if already created
            if (mediaFrame) {
                mediaFrame.open();
                return;
            }

            // Create the media frame
            mediaFrame = wp.media({
                title: 'Select Background Images',
                button: {
                    text: 'Use these images'
                },
                multiple: true,
                library: {
                    type: 'image'
                }
            });

            // When images are selected
            mediaFrame.on('select', () => {
                const selection = mediaFrame.state().get('selection');
                const attachments = selection.toJSON();

                // Get current IDs
                const $input = $('#helperbox_adminlogin_bgimages');
                const currentValue = $input.val();
                const currentIds = currentValue ? currentValue.split(',').map(Number).filter(Boolean) : [];

                // Add new IDs
                const newIds = attachments.map((attachment) => attachment.id);

                // Merge and remove duplicates
                const allIds = [...new Set(currentIds.concat(newIds))];

                // Update hidden input
                $input.val(allIds.join(','));

                // Update preview
                const $preview = $('.helperbox-bg-images-preview');
                $preview.empty();
                attachments.forEach((attachment) => {
                    const imgUrl = attachment.sizes && attachment.sizes.medium
                        ? attachment.sizes.medium.url
                        : attachment.url;

                    const $img = $('<img>', {
                        src: imgUrl,
                        style: 'margin:5px; max-height:150px; max-width:150px; border:1px solid #ccc; border-radius:4px;'
                    });

                    $preview.append($img);
                });

                // Show remove button
                $('.helperbox-remove-all-bg-images').show();
            });

            // Open the frame
            mediaFrame.open();
        });

        // Remove all background images
        $('.helperbox-remove-all-bg-images').on('click', function (e) {
            e.preventDefault();
            $('#helperbox_adminlogin_bgimages').val('');
            $('.helperbox-bg-images-preview').empty();
            $(this).hide();
        });
    }
});


