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

/***/ "./blocks/glossary/glossary.js":
/*!*************************************!*\
  !*** ./blocks/glossary/glossary.js ***!
  \*************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

eval("{__webpack_require__.r(__webpack_exports__);\njQuery($ => {\n  let searchValue = '';\n  const searchParams = new URLSearchParams(window.location.search);\n  if (searchParams.has(\"search\")) {\n    searchValue = searchParams.get(\"search\");\n  }\n  const tableQuery = {\n    pageLength: 25,\n    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, \"All\"]],\n    dom: \"lrtip\",\n    ordering: false,\n    fnDrawCallback() {\n      const totalPages = this.api().page.info().pages;\n      if (1 === totalPages) {\n        $(\".dataTables_paginate\").hide();\n      } else {\n        $(\".dataTables_paginate\").show();\n      }\n    }\n  };\n  const table = $(\"#glossary-table\").DataTable(tableQuery);\n  $(\".glossary-search input\").on(\"keyup\", function () {\n    table.search(this.value).draw();\n  });\n  if (searchValue) {\n    table.search(searchValue).draw();\n  }\n});\n\n//# sourceURL=webpack:///./blocks/glossary/glossary.js?\n}");

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
/******/ 	__webpack_modules__["./blocks/glossary/glossary.js"](0,__webpack_exports__,__webpack_require__);
/******/ 	
/******/ })()
;