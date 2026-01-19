/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./js/admin.js":
/*!*********************!*\
  !*** ./js/admin.js ***!
  \*********************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

eval("{__webpack_require__.r(__webpack_exports__);\njQuery($ => {\n  // For settings_page_helperbox\n  if ('undefined' !== typeof helperboxJS && helperboxJS.settings_page_helperbox) {\n    // \n    if ('breadcrumb' == helperboxJS.active_tab) {\n      if (document.getElementById('helperbox_breadcrumb_remove_condition_editor')) {\n        const breadcrumbRemoveCondition = ace.edit(\"helperbox_breadcrumb_remove_condition_editor\"); // div id to convert into editor\n        breadcrumbRemoveCondition.session.setMode(\"ace/mode/json\"); // Set mode for JSON syntax highlighting\n        breadcrumbRemoveCondition.setTheme(\"ace/theme/monokai\"); // Set a theme\n        const removeConditionAlertMsg = \"Please enter valid JSON for Remove Breadcrumb Condition.\";\n        if (document.getElementById('helperbox_breadcrumb_remove_condition').value) {\n          formatJSON(breadcrumbRemoveCondition, removeConditionAlertMsg);\n        }\n        // format json values\n        $('#set_jsonformat_remove_condition').on('click', function () {\n          formatJSON(breadcrumbRemoveCondition, removeConditionAlertMsg);\n        });\n\n        // handle submit \n        let submitBtn = document.querySelector('.helperbox-setting-form-breadcrumb #submit');\n        submitBtn.addEventListener('click', function (e) {\n          const removeCondEditorValue = breadcrumbRemoveCondition.getValue();\n          let valueIsValidJSON = false;\n          if (removeCondEditorValue) {\n            if (isValidJSON(removeCondEditorValue)) {\n              valueIsValidJSON = true;\n            } else {\n              e.stopPropagation();\n              e.preventDefault();\n              console.error('invalid remove condition json value.');\n            }\n            formatJSON(breadcrumbRemoveCondition, removeConditionAlertMsg);\n          }\n          if (valueIsValidJSON || '' == removeCondEditorValue) {\n            document.getElementById('helperbox_breadcrumb_remove_condition').value = removeCondEditorValue;\n          }\n        });\n      }\n    }\n    // adminlogin\n    if ('adminlogin' == helperboxJS.active_tab) {\n      let bgImageFrame;\n      let logoImageFrame;\n\n      // set color picker\n      $(\"#helperbox_adminlogin_formbgcolor\").wpColorPicker();\n\n      // Action when add btn is clicked\n      $('#helperbox_adminlogin_bgimages_addBtn').on('click', function (e) {\n        e.preventDefault();\n        // get preview area\n        const previewSection = $('.helperbox_adminlogin_bgimages-preview');\n        const fieldName = $(this).attr('field-name');\n        mediaFrame(fieldName, bgImageFrame, 'Select Login Background Image', previewSection, false);\n      });\n\n      // Action when add btn is clicked\n      $('#helperbox_adminlogin_logo_addBtn').on('click', function (e) {\n        e.preventDefault();\n        const previewSection = $('.helperbox_adminlogin_logo-preview');\n        const fieldName = $(this).attr('field-name');\n        mediaFrame(fieldName, logoImageFrame, 'Select Login Form Logo', previewSection, false);\n      });\n\n      // Remove all\n      $(document).on('click', '.helperbox-delete-all-media', function (e) {\n        e.preventDefault();\n        const fieldName = $(this).attr('field-name');\n        $(\".\" + fieldName + \"-preview\").empty();\n        $(this).hide();\n      });\n\n      // Handle remove button click\n      $(document).on('click', '.remove-image', function (e) {\n        e.preventDefault();\n        let attachmentId = $(this).data('attachment-id');\n        if (attachmentId) {\n          $('.selected-image-' + attachmentId).remove();\n        }\n      });\n    }\n  }\n\n  // preview attachment\n  function previewAttachment(fieldName, attachment) {\n    if (!fieldName || !attachment) {\n      return;\n    }\n    // Safely escape attributes\n    let imgSrc = attachment.url.replace(/&/g, '&amp;').replace(/\"/g, '&quot;');\n    let attachmentId = parseInt(attachment.id, 10);\n\n    // Unique container for this image\n    let containerClass = 'selected-image-' + attachmentId;\n    return '<div class=\"selected-image ' + containerClass + '\" >' + '<img src=\"' + imgSrc + '\" ' + 'alt=\"Selected image\">' +\n    // Hidden input to store attachment ID (for saving multiple)\n    '<input type=\"hidden\" name=\"' + fieldName + '[]\" value=\"' + attachmentId + '\">' +\n    // Remove button\n    '<a href=\"#\" class=\"remove-image button button-secondary button-small\" ' + 'data-attachment-id=\"' + attachmentId + '\" title=\"Remove image\">×</a>' + '</div>';\n  }\n\n  // media frame\n  function mediaFrame(fieldName, frame, frameTitle, previewSection, isMultiple = false) {\n    // Reuse existing frame if already created\n    if (frame) {\n      frame.open();\n      return;\n    }\n\n    // Create the media frame\n    frame = wp.media({\n      title: frameTitle,\n      button: {\n        text: 'Use this image'\n      },\n      multiple: isMultiple,\n      library: {\n        type: 'image'\n      }\n    });\n\n    // When images are selected\n    frame.on('select', function () {\n      if (isMultiple) {\n        let attachments = frame.state().get('selection').toJSON();\n        attachments.forEach(attachment => {\n          previewSection.append(previewAttachment(fieldName, attachment));\n        });\n      } else {\n        let attachment = frame.state().get('selection').first().toJSON();\n        previewSection.html(previewAttachment(fieldName, attachment));\n      }\n      // Show \"Remove All\" button if multiple\n      if (isMultiple) {\n        // $('.helperbox-delete-all-media').show();\n      }\n    });\n    frame.open();\n  }\n\n  // check if value is valid json\n  const isValidJSON = str => {\n    try {\n      JSON.parse(str);\n      return true;\n    } catch (e) {\n      return false;\n    }\n  };\n\n  // format JSON values\n  function formatJSON(editor, invalidAlertMsg = \"Invalid JSON!\") {\n    try {\n      // Get editor value and parse JSON\n      let content = editor.getValue();\n      if (!content) {\n        return;\n      }\n      let json = JSON.parse(content);\n\n      // Format JSON with indentation\n      let formatted = JSON.stringify(json, null, 4);\n\n      // Set formatted JSON back to editor\n      editor.setValue(formatted, 1); // 1 moves cursor to the end of the text\n    } catch (e) {\n      alert(invalidAlertMsg);\n    }\n  }\n\n  // === END ===\n});\n\n//# sourceURL=webpack:///./js/admin.js?\n}");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The require scope
/******/ 	var __webpack_require__ = {};
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./js/admin.js"](0,__webpack_exports__,__webpack_require__);
/******/ 	
/******/ })()
;