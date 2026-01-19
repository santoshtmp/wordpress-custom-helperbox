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

/***/ "./blocks/helperbox-blocks.js":
/*!************************************!*\
  !*** ./blocks/helperbox-blocks.js ***!
  \************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

eval("{__webpack_require__.r(__webpack_exports__);\n/**\n * External dependencies\n */\n// import $ from 'jquery';\n\n/**\n * Unregister specific embed block variations.\n *  https://developer.wordpress.org/news/2024/01/29/how-to-disable-specific-blocks-in-wordpress/\n *  https://developer.wordpress.org/reference/hooks/allowed_block_types_all/\n * \n */\nwp.domReady(function () {\n  // \n  const unregisterBlocks = ['core/legacy-widget', 'core/widget-group', 'core/archives', 'core/avatar', 'core/block', 'core/calendar', 'core/categories', 'core/footnotes', 'core/navigation', 'core/query', 'core/query-title', 'core/latest-posts', 'core/page-list', 'core/tag-cloud', 'core/post-terms', 'core/freeform'];\n  unregisterBlocks.forEach(block => {\n    if (wp.blocks.getBlockType(block)) {\n      wp.blocks.unregisterBlockType(block);\n    }\n  });\n\n  // Disable block variation for the core/group block\n  const groupVariations = ['group-stack', 'group-row'];\n  groupVariations.forEach(variation => {\n    wp.blocks.unregisterBlockVariation('core/group', variation);\n  });\n  // Disable block variations for the core/embed block\n  const embedVariations = ['wordpress', 'crowdsignal', 'soundcloud', 'spotify', 'flickr', 'vimeo', 'animoto', 'cloudup', 'collegehumor', 'dailymotion', 'funnyordie', 'hulu', 'imgur', 'issuu', 'kickstarter', 'meetup-com', 'mixcloud', 'photobucket', 'polldaddy', 'reddit', 'reverbnation', 'screencast', 'scribd', 'slideshare', 'smugmug', 'speaker', 'ted', 'tumblr', 'videopress', 'wordpress-tv', 'speaker-deck', 'amazon-kindle', 'wolfram-cloud'];\n  embedVariations.forEach(variation => {\n    wp.blocks.unregisterBlockVariation('core/embed', variation);\n  });\n});\n\n//# sourceURL=webpack:///./blocks/helperbox-blocks.js?\n}");

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
/******/ 	__webpack_modules__["./blocks/helperbox-blocks.js"](0,__webpack_exports__,__webpack_require__);
/******/ 	
/******/ })()
;