jQuery(($) => {

    // For settings_page_helperbox
    if ('undefined' !== typeof helperboxJS && helperboxJS.settings_page_helperbox) {

        // 
        if ('breadcrumb' == helperboxJS.active_tab) {
            if (document.getElementById('helperbox_breadcrumb_remove_condition_editor')) {
                const breadcrumbRemoveCondition = ace.edit("helperbox_breadcrumb_remove_condition_editor"); // div id to convert into editor
                breadcrumbRemoveCondition.session.setMode("ace/mode/json"); // Set mode for JSON syntax highlighting
                breadcrumbRemoveCondition.setTheme("ace/theme/monokai");  // Set a theme
                const removeConditionAlertMsg = "Please enter valid JSON for Remove Breadcrumb Condition.";
                if (document.getElementById('helperbox_breadcrumb_remove_condition').value) {
                    formatJSON(breadcrumbRemoveCondition, removeConditionAlertMsg);
                }
                // format json values
                $('#set_jsonformat_remove_condition').on('click', function () {
                    formatJSON(breadcrumbRemoveCondition, removeConditionAlertMsg);
                });

                // handle submit 
                let submitBtn = document.querySelector('.helperbox-setting-form-breadcrumb #submit');
                submitBtn.addEventListener('click', function (e) {
                    const removeCondEditorValue = breadcrumbRemoveCondition.getValue();
                    let valueIsValidJSON = false;
                    if (removeCondEditorValue) {
                        if (isValidJSON(removeCondEditorValue)) {
                            valueIsValidJSON = true;
                        } else {
                            e.stopPropagation();
                            e.preventDefault();
                            console.error('invalid remove condition json value.');
                        }
                        formatJSON(breadcrumbRemoveCondition, removeConditionAlertMsg);
                    }
                    if (valueIsValidJSON || ('' == removeCondEditorValue)) {
                        document.getElementById('helperbox_breadcrumb_remove_condition').value = removeCondEditorValue;
                    }
                });
            }


        }
        // adminlogin
        if ('adminlogin' == helperboxJS.active_tab) {
            let bgImageFrame;
            let logoImageFrame;

            // set color picker
            $("#helperbox_adminlogin_formbgcolor").wpColorPicker();

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
                let attachmentId = $(this).data('attachment-id');
                if (attachmentId) {
                    $('.selected-image-' + attachmentId).remove();
                }
            });
        }
    }


    // preview attachment
    function previewAttachment(fieldName, attachment) {
        if (!fieldName || !attachment) {
            return;
        }
        // Safely escape attributes
        let imgSrc = attachment.url.replace(/&/g, '&amp;').replace(/"/g, '&quot;');
        let attachmentId = parseInt(attachment.id, 10);

        // Unique container for this image
        let containerClass = 'selected-image-' + attachmentId;

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

    // media frame
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
                let attachments = frame.state().get('selection').toJSON();
                attachments.forEach((attachment) => {
                    previewSection.append(previewAttachment(fieldName, attachment));
                });
            } else {
                let attachment = frame.state().get('selection').first().toJSON();
                previewSection.html(previewAttachment(fieldName, attachment));
            }
            // Show "Remove All" button if multiple
            if (isMultiple) {
                // $('.helperbox-delete-all-media').show();
            }
        });

        frame.open();

    }

    // check if value is valid json
    const isValidJSON = str => {
        try {
            JSON.parse(str);
            return true;
        } catch (e) {
            return false;
        }
    };

    // format JSON values
    function formatJSON(editor, invalidAlertMsg = "Invalid JSON!") {
        try {
            // Get editor value and parse JSON
            let content = editor.getValue();
            if (!content) {
                return;
            }
            let json = JSON.parse(content);

            // Format JSON with indentation
            let formatted = JSON.stringify(json, null, 4);

            // Set formatted JSON back to editor
            editor.setValue(formatted, 1); // 1 moves cursor to the end of the text
        } catch (e) {
            alert(invalidAlertMsg);
        }
    }

    // === END ===
});


