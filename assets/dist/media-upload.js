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

jQuery(document).ready(function ($) {
  // Upload Button
  $('.wp-express.field.attachment .btn-upload').click(function (e) {
    var parent = $(e.currentTarget).parent('.wp-express.field.attachment');
    var parentId = parent.attr('data-parent');
    var isSingle = $(e.currentTarget)[0].hasAttribute('data-single'); // Prepare media library

    var frame = wp.media && wp.media({
      title: 'Select or Upload Media',
      button: {
        text: 'Select'
      },
      multiple: !isSingle
    });
    frame.on('open', function () {
      parent.find('.attachment__items__item').each(function (_, elm) {
        var attachmentId = $(elm).find('input').val();
        frame.state().get('selection').add(wp.media.attachment(attachmentId));
      });
    }).on('select', function () {
      var attachments = frame.state().get('selection').models;
      Object(_media_upload_attachment__WEBPACK_IMPORTED_MODULE_0__["setAttachment"])(parentId, attachments, isSingle);
    }).open();
    e.preventDefault();
  });
  Object(_media_upload_attachment__WEBPACK_IMPORTED_MODULE_0__["bindRemoveAction"])(jQuery('.wp-express.field.attachment .btn-remove'));
});

/***/ }),

/***/ "./assets/scripts/media-upload/attachment.js":
/*!***************************************************!*\
  !*** ./assets/scripts/media-upload/attachment.js ***!
  \***************************************************/
/*! exports provided: removeAttachment, bindRemoveAction, setAttachment */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "removeAttachment", function() { return removeAttachment; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "bindRemoveAction", function() { return bindRemoveAction; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "setAttachment", function() { return setAttachment; });
function removeAttachment(parent, index) {
  jQuery("section[data-parent=\"".concat(parent, "\"] .attachment__items__item[data-index=\"").concat(index, "\"]")).remove();
}
function bindRemoveAction(dom) {
  dom.on('click', function (e) {
    var parent = jQuery(e.currentTarget).attr('data-parent');
    var index = jQuery(e.currentTarget).attr('data-index');
    e.preventDefault();
    removeAttachment(parent, index);
  });
}

function setSingleAttachment(parent, attachmentId, url, index) {
  var container = jQuery('<section />').addClass('attachment__items__item').attr('data-index', index);
  var input = jQuery('<input />').attr({
    name: "".concat(parent, "[").concat(index, "]"),
    type: 'hidden'
  }).val(attachmentId);
  var div = jQuery('<div />').addClass('img-container').css('background-image', "url('".concat(url, "')"));
  var button = jQuery('<button />').addClass('btn-remove').attr({
    'data-parent': parent,
    'data-index': index
  });
  var span = jQuery('<span />').addClass('dashicons dashicons-no');
  button.append(span);
  container.append(input).append(div).append(button);
  bindRemoveAction(button);
  jQuery("section[data-parent=\"".concat(parent, "\"] .attachment__items")).append(container);
}

function setAttachment(parent, attachments, isSingle) {
  jQuery("section[data-parent=\"".concat(parent, "\"] .attachment__items")).html('');
  attachments.filter(function (_, index) {
    return isSingle ? index === 0 : true;
  }) // eslint-disable-line no-confusing-arrow
  .forEach(function (attachment, index) {
    return setSingleAttachment(parent, attachment.id, attachment.attributes.url, index);
  });
}

/***/ })

/******/ });
//# sourceMappingURL=media-upload.js.map