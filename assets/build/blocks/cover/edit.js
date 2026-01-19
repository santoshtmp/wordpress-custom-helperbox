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

/***/ "./blocks/cover/edit.js":
/*!******************************!*\
  !*** ./blocks/cover/edit.js ***!
  \******************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

eval("{__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ \"@wordpress/block-editor\");\n/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/blocks */ \"@wordpress/blocks\");\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/components */ \"@wordpress/components\");\n/* harmony import */ var _wordpress_server_side_render__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/server-side-render */ \"@wordpress/server-side-render\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/**\n * Retrieves the translation of text.\n *\n * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/\n */\n\n\n/**\n * React hook that is used to mark the block wrapper element.\n * It provides all the necessary props like the class name.\n *\n * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops\n */\n\n\n/**\n * Registers a new block provided a unique name and an object defining its behavior.\n *\n * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/\n */\n\n\n/**\n * Component\n * \n * @see https://developer.wordpress.org/block-editor/reference-guides/components/\n */\n\n\n/**\n * ServerSideRender\n * \n */\n\n\n\n// Use global jQuery from WordPress\nconst $ = window.jQuery;\n\n/**\n * const variables\n */\nconst thisBlockName = 'helperbox/cover';\n\n/**\n * The edit function describes the structure of your block in the context of the\n * editor. This represents what the editor will render when the block is used.\n *\n * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit\n * @see https://wordpress.github.io/gutenberg/?path=/docs/docs-introduction--page \n * @return {Element} Element to render.\n */\n// export default function Edit() {\n// \treturn (\n// \t\t<p { ...useBlockProps() }>\n// \t\t\t{ __( 'Todo List – hello from the editor!', 'todo-list' ) }\n// \t\t</p>\n// \t);\n// }\n\nfunction Edit({\n  attributes,\n  setAttributes\n}) {\n  const {\n    heading,\n    text,\n    paragraphText,\n    minHeight,\n    bgImage,\n    defaultBg,\n    ctas = []\n  } = attributes;\n  const imageUrl = bgImage?.sizes?.large?.url || bgImage?.sizes?.medium?.url || bgImage?.url;\n  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)({\n    className: 'helperbox-cover-editor'\n  });\n\n  /**\n   * CTA helpers\n   */\n  const updateCTA = (index, field, value) => {\n    const newCtas = [...ctas];\n    newCtas[index] = {\n      ...newCtas[index],\n      [field]: value\n    };\n    setAttributes({\n      ctas: newCtas\n    });\n  };\n  const addCTA = () => {\n    setAttributes({\n      ctas: [...ctas, {\n        text: '',\n        url: '',\n        variant: 'primary',\n        newTab: false\n      }]\n    });\n  };\n  const removeCTA = index => {\n    const newCtas = ctas.filter((_, i) => i !== index);\n    setAttributes({\n      ctas: newCtas\n    });\n  };\n  const moveCTA = (from, to) => {\n    if (to < 0 || to >= ctas.length) {\n      return;\n    }\n    const newCtas = [...ctas];\n    const temp = newCtas[from];\n    newCtas[from] = newCtas[to];\n    newCtas[to] = temp;\n    setAttributes({\n      ctas: newCtas\n    });\n  };\n\n  /* =========================\n   * Auto-remove invalid CTAs\n   * ========================= */\n  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.useEffect)(() => {\n    const cleaned = ctas.filter(cta => cta.text && cta.url);\n    if (cleaned.length !== ctas.length) {\n      setAttributes({\n        ctas: cleaned\n      });\n    }\n  }, []);\n\n  /**\n   * Return\n   */\n  return /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.PanelBody, {\n    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Cover Settings', 'helperbox'),\n    initialOpen: true\n  }, /*#__PURE__*/React.createElement(\"div\", {\n    className: \"helperbox-edit-media-field-group-control\",\n    style: {\n      marginBottom: '12px'\n    }\n  }, imageUrl && /*#__PURE__*/React.createElement(\"div\", {\n    className: \"helperbox-edit-media-preview\"\n  }, /*#__PURE__*/React.createElement(\"img\", {\n    src: imageUrl,\n    alt: \"\",\n    style: {\n      width: '100px',\n      height: 'auto',\n      objectFit: 'cover',\n      borderRadius: '4px'\n    }\n  })), /*#__PURE__*/React.createElement(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUploadCheck, null, /*#__PURE__*/React.createElement(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUpload, {\n    onSelect: media => setAttributes({\n      bgImage: media\n    }),\n    allowedTypes: ['image'],\n    value: bgImage?.id,\n    render: ({\n      open\n    }) => /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.Button, {\n      onClick: open,\n      variant: \"secondary\"\n    }, bgImage ? 'Replace Background Image' : 'Upload Background Image')\n  })), bgImage && /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.Button, {\n    onClick: () => setAttributes({\n      bgImage: null\n    }),\n    variant: \"secondary\",\n    isDestructive: true\n  }, \"Remove image\"), !bgImage && /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.ToggleControl, {\n    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Use default background', 'helperbox'),\n    checked: defaultBg,\n    onChange: value => setAttributes({\n      defaultBg: value\n    })\n  })), /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.RangeControl, {\n    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Min Height', 'helperbox'),\n    value: minHeight,\n    min: 300,\n    max: 900,\n    onChange: value => setAttributes({\n      minHeight: value\n    })\n  }), /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextControl, {\n    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Heading', 'helperbox'),\n    value: heading,\n    onChange: value => setAttributes({\n      heading: value\n    })\n  }), /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextareaControl, {\n    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Text', 'helperbox'),\n    value: text,\n    onChange: value => setAttributes({\n      text: value\n    })\n  }), /*#__PURE__*/React.createElement(\"div\", {\n    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('CTA Buttons', 'helperbox')\n  }, ctas.map((cta, index) => /*#__PURE__*/React.createElement(\"div\", {\n    key: index,\n    style: {\n      border: '1px solid #ddd',\n      padding: '12px',\n      marginBottom: '12px',\n      borderRadius: '4px'\n    }\n  }, /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextControl, {\n    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Button Text', 'helperbox'),\n    value: cta.text,\n    help: !cta.text ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Button Text is required', 'helperbox') : '',\n    __experimentalShowError: !cta.text,\n    onChange: value => updateCTA(index, 'text', value)\n  }), /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextControl, {\n    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Button Link', 'helperbox'),\n    value: cta.url,\n    onChange: url => updateCTA(index, 'url', url)\n  }), /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.SelectControl, {\n    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Button Variant', 'helperbox'),\n    value: cta.variant,\n    options: [{\n      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Primary', 'helperbox'),\n      value: 'primary'\n    }, {\n      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Secondary', 'helperbox'),\n      value: 'secondary'\n    }, {\n      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Outline', 'helperbox'),\n      value: 'outline'\n    }],\n    onChange: value => updateCTA(index, 'variant', value)\n  }), /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.ToggleControl, {\n    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Open in new tab', 'helperbox'),\n    checked: cta.newTab,\n    onChange: value => updateCTA(index, 'newTab', value)\n  }), /*#__PURE__*/React.createElement(\"div\", {\n    style: {\n      display: 'flex',\n      gap: '8px',\n      marginTop: '8px'\n    }\n  }, /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.Button, {\n    variant: \"secondary\",\n    onClick: () => moveCTA(index, index - 1),\n    disabled: index === 0\n  }, \"\\u2191\"), /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.Button, {\n    variant: \"secondary\",\n    onClick: () => moveCTA(index, index + 1),\n    disabled: index === ctas.length - 1\n  }, \"\\u2193\"), /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.Button, {\n    variant: \"secondary\",\n    isDestructive: true,\n    onClick: () => removeCTA(index)\n  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Remove', 'helperbox'))))), /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.Button, {\n    variant: \"primary\",\n    onClick: addCTA\n  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Add CTA Button', 'helperbox'))))), /*#__PURE__*/React.createElement(\"div\", blockProps, /*#__PURE__*/React.createElement(_wordpress_server_side_render__WEBPACK_IMPORTED_MODULE_4__, {\n    block: thisBlockName,\n    attributes: attributes,\n    LoadingResponsePlaceholder: () => /*#__PURE__*/React.createElement(\"div\", {\n      style: {\n        padding: '20px',\n        textAlign: 'center'\n      }\n    }, /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.Spinner, null))\n  })));\n}\n\n/**\n * Every block starts by registering a new block type definition.\n *\n * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/\n */\n(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_2__.registerBlockType)(thisBlockName, {\n  /**\n   * @see ./edit.js\n   */\n  edit: Edit\n});\n\n//# sourceURL=webpack:///./blocks/cover/edit.js?\n}");

/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/***/ (function(module) {

module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ "@wordpress/blocks":
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
/***/ (function(module) {

module.exports = window["wp"]["blocks"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ (function(module) {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ (function(module) {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ (function(module) {

module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "@wordpress/server-side-render":
/*!******************************************!*\
  !*** external ["wp","serverSideRender"] ***!
  \******************************************/
/***/ (function(module) {

module.exports = window["wp"]["serverSideRender"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Check if module exists (development only)
/******/ 		if (__webpack_modules__[moduleId] === undefined) {
/******/ 			var e = new Error("Cannot find module '" + moduleId + "'");
/******/ 			e.code = 'MODULE_NOT_FOUND';
/******/ 			throw e;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
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
/******/ 	var __webpack_exports__ = __webpack_require__("./blocks/cover/edit.js");
/******/ 	
/******/ })()
;