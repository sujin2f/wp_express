/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./assets/scripts/media-upload.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/scripts/media-upload.js":
/*!****************************************!*\
  !*** ./assets/scripts/media-upload.js ***!
  \****************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _media_upload_attachment__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./media-upload/attachment */ "./assets/scripts/media-upload/attachment.js");

jQuery(document).ready(() => {
  const frame = wp.media && wp.media({
    title: 'Select or Upload Media Of Your Chosen Persuasion',
    button: {
      text: 'Use this media'
    },
    multiple: false
  });
  jQuery('.wp-express.field.attachment .btn-upload').click(e => {
    const id = jQuery(e.currentTarget).parent('.wp-express.field.attachment').attr('data-id');
    e.preventDefault();
    frame.on('select', () => {
      const attachment = frame.state().get('selection').first().toJSON();
      Object(_media_upload_attachment__WEBPACK_IMPORTED_MODULE_0__["setAttachment"])(id, attachment);
    });
    frame.open();
  });
  jQuery('.wp-express.field.attachment .btn-remove').on('click', e => {
    const id = jQuery(e.currentTarget).parent('.wp-express.field.attachment').attr('data-id');
    e.preventDefault();
    Object(_media_upload_attachment__WEBPACK_IMPORTED_MODULE_0__["removeAttachment"])(id);
  });
  jQuery('.wp-express.field.attachment .img-container').on('click', e => {
    const id = jQuery(e.currentTarget).parent('.wp-express.field.attachment').attr('data-id');
    e.preventDefault();
    Object(_media_upload_attachment__WEBPACK_IMPORTED_MODULE_0__["removeAttachment"])(id);
  });
});

/***/ }),

/***/ "./assets/scripts/media-upload/attachment.js":
/*!***************************************************!*\
  !*** ./assets/scripts/media-upload/attachment.js ***!
  \***************************************************/
/*! exports provided: setAttachment, removeAttachment */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "setAttachment", function() { return setAttachment; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "removeAttachment", function() { return removeAttachment; });
function setAttachment(id, attachment) {
  jQuery(`section[data-id="${id}"] .img-container`).attr('style', `background-image: url('${attachment.url}');`).removeClass('hidden');
  jQuery(`section[data-id="${id}"] input[type="hidden"]`).val(attachment.id);
  jQuery(`section[data-id="${id}"] .btn-upload`).addClass('hidden');
  jQuery(`section[data-id="${id}"] .btn-remove`).removeClass('hidden');
}
function removeAttachment(id) {
  jQuery(`section[data-id="${id}"] .img-container`).attr('style', '').addClass('hidden');
  jQuery(`section[data-id="${id}"] input[type="hidden"]`).val('');
  jQuery(`section[data-id="${id}"] .btn-upload`).removeClass('hidden');
  jQuery(`section[data-id="${id}"] .btn-remove`).addClass('hidden');
}

/***/ })

/******/ });
//# sourceMappingURL=media-upload.js.map