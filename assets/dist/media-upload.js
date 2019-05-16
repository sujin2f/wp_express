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
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


function uploadButton() {
  jQuery('.wp_express.btn-upload').click(function (e) {
    var id = jQuery(e.currentTarget).attr('data-id');
    e.preventDefault();

    var frame = wp.media({
      title: 'Select or Upload Media Of Your Chosen Persuasion',
      button: { text: 'Use this media' },
      multiple: false
    });

    frame.on('select', function () {
      var attachment = frame.state().get('selection').first().toJSON();
      jQuery('#' + id + '-custom-img-container').append('<img src="' + attachment.url + '" width="150" />');
      jQuery('#' + id + '-custom-img-id').val(attachment.id);

      jQuery('#' + id + '-upload-custom-img').addClass('hidden');
      jQuery('#' + id + '-delete-custom-img').removeClass('hidden');
    });

    frame.open();
  });
}

function removeButton() {
  jQuery('.wp_express.btn-remove').on('click', function (e) {
    var id = jQuery(e.currentTarget).attr('data-id');
    e.preventDefault();

    jQuery('#' + id + '-custom-img-container').html('');
    jQuery('#' + id + '-custom-img-id').val('');

    jQuery('#' + id + '-upload-custom-img').removeClass('hidden');
    jQuery('#' + id + '-delete-custom-img').addClass('hidden');
  });
}

jQuery(document).ready(function () {
  uploadButton();
  removeButton();
});

/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL3NjcmlwdHMvbWVkaWEtdXBsb2FkLmpzIl0sIm5hbWVzIjpbInVwbG9hZEJ1dHRvbiIsImpRdWVyeSIsImNsaWNrIiwiZSIsImlkIiwiY3VycmVudFRhcmdldCIsImF0dHIiLCJwcmV2ZW50RGVmYXVsdCIsImZyYW1lIiwid3AiLCJtZWRpYSIsInRpdGxlIiwiYnV0dG9uIiwidGV4dCIsIm11bHRpcGxlIiwib24iLCJhdHRhY2htZW50Iiwic3RhdGUiLCJnZXQiLCJmaXJzdCIsInRvSlNPTiIsImFwcGVuZCIsInVybCIsInZhbCIsImFkZENsYXNzIiwicmVtb3ZlQ2xhc3MiLCJvcGVuIiwicmVtb3ZlQnV0dG9uIiwiaHRtbCIsImRvY3VtZW50IiwicmVhZHkiXSwibWFwcGluZ3MiOiI7QUFBQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLGtEQUEwQyxnQ0FBZ0M7QUFDMUU7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxnRUFBd0Qsa0JBQWtCO0FBQzFFO0FBQ0EseURBQWlELGNBQWM7QUFDL0Q7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlEQUF5QyxpQ0FBaUM7QUFDMUUsd0hBQWdILG1CQUFtQixFQUFFO0FBQ3JJO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsbUNBQTJCLDBCQUEwQixFQUFFO0FBQ3ZELHlDQUFpQyxlQUFlO0FBQ2hEO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLDhEQUFzRCwrREFBK0Q7O0FBRXJIO0FBQ0E7OztBQUdBO0FBQ0E7Ozs7Ozs7Ozs7Ozs7OztBQ2xGQSxTQUFTQSxZQUFULEdBQXdCO0FBQ3RCQyxTQUFPLHdCQUFQLEVBQWlDQyxLQUFqQyxDQUF1QyxVQUFDQyxDQUFELEVBQU87QUFDNUMsUUFBTUMsS0FBS0gsT0FBT0UsRUFBRUUsYUFBVCxFQUF3QkMsSUFBeEIsQ0FBNkIsU0FBN0IsQ0FBWDtBQUNBSCxNQUFFSSxjQUFGOztBQUVBLFFBQU1DLFFBQVFDLEdBQUdDLEtBQUgsQ0FBUztBQUNyQkMsYUFBTyxrREFEYztBQUVyQkMsY0FBUSxFQUFFQyxNQUFNLGdCQUFSLEVBRmE7QUFHckJDLGdCQUFVO0FBSFcsS0FBVCxDQUFkOztBQU1BTixVQUFNTyxFQUFOLENBQVMsUUFBVCxFQUFtQixZQUFNO0FBQ3ZCLFVBQU1DLGFBQWFSLE1BQU1TLEtBQU4sR0FBY0MsR0FBZCxDQUFrQixXQUFsQixFQUErQkMsS0FBL0IsR0FBdUNDLE1BQXZDLEVBQW5CO0FBQ0FuQixtQkFBV0csRUFBWCw0QkFDR2lCLE1BREgsZ0JBQ3VCTCxXQUFXTSxHQURsQztBQUVBckIsbUJBQVdHLEVBQVgscUJBQStCbUIsR0FBL0IsQ0FBbUNQLFdBQVdaLEVBQTlDOztBQUVBSCxtQkFBV0csRUFBWCx5QkFBbUNvQixRQUFuQyxDQUE0QyxRQUE1QztBQUNBdkIsbUJBQVdHLEVBQVgseUJBQW1DcUIsV0FBbkMsQ0FBK0MsUUFBL0M7QUFDRCxLQVJEOztBQVVBakIsVUFBTWtCLElBQU47QUFDRCxHQXJCRDtBQXNCRDs7QUFFRCxTQUFTQyxZQUFULEdBQXdCO0FBQ3RCMUIsU0FBTyx3QkFBUCxFQUFpQ2MsRUFBakMsQ0FBb0MsT0FBcEMsRUFBNkMsVUFBQ1osQ0FBRCxFQUFPO0FBQ2xELFFBQU1DLEtBQUtILE9BQU9FLEVBQUVFLGFBQVQsRUFBd0JDLElBQXhCLENBQTZCLFNBQTdCLENBQVg7QUFDQUgsTUFBRUksY0FBRjs7QUFFQU4saUJBQVdHLEVBQVgsNEJBQXNDd0IsSUFBdEMsQ0FBMkMsRUFBM0M7QUFDQTNCLGlCQUFXRyxFQUFYLHFCQUErQm1CLEdBQS9CLENBQW1DLEVBQW5DOztBQUVBdEIsaUJBQVdHLEVBQVgseUJBQW1DcUIsV0FBbkMsQ0FBK0MsUUFBL0M7QUFDQXhCLGlCQUFXRyxFQUFYLHlCQUFtQ29CLFFBQW5DLENBQTRDLFFBQTVDO0FBQ0QsR0FURDtBQVVEOztBQUVEdkIsT0FBTzRCLFFBQVAsRUFBaUJDLEtBQWpCLENBQXVCLFlBQU07QUFDM0I5QjtBQUNBMkI7QUFDRCxDQUhELEUiLCJmaWxlIjoibWVkaWEtdXBsb2FkLmpzIiwic291cmNlc0NvbnRlbnQiOlsiIFx0Ly8gVGhlIG1vZHVsZSBjYWNoZVxuIFx0dmFyIGluc3RhbGxlZE1vZHVsZXMgPSB7fTtcblxuIFx0Ly8gVGhlIHJlcXVpcmUgZnVuY3Rpb25cbiBcdGZ1bmN0aW9uIF9fd2VicGFja19yZXF1aXJlX18obW9kdWxlSWQpIHtcblxuIFx0XHQvLyBDaGVjayBpZiBtb2R1bGUgaXMgaW4gY2FjaGVcbiBcdFx0aWYoaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0pIHtcbiBcdFx0XHRyZXR1cm4gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0uZXhwb3J0cztcbiBcdFx0fVxuIFx0XHQvLyBDcmVhdGUgYSBuZXcgbW9kdWxlIChhbmQgcHV0IGl0IGludG8gdGhlIGNhY2hlKVxuIFx0XHR2YXIgbW9kdWxlID0gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0gPSB7XG4gXHRcdFx0aTogbW9kdWxlSWQsXG4gXHRcdFx0bDogZmFsc2UsXG4gXHRcdFx0ZXhwb3J0czoge31cbiBcdFx0fTtcblxuIFx0XHQvLyBFeGVjdXRlIHRoZSBtb2R1bGUgZnVuY3Rpb25cbiBcdFx0bW9kdWxlc1ttb2R1bGVJZF0uY2FsbChtb2R1bGUuZXhwb3J0cywgbW9kdWxlLCBtb2R1bGUuZXhwb3J0cywgX193ZWJwYWNrX3JlcXVpcmVfXyk7XG5cbiBcdFx0Ly8gRmxhZyB0aGUgbW9kdWxlIGFzIGxvYWRlZFxuIFx0XHRtb2R1bGUubCA9IHRydWU7XG5cbiBcdFx0Ly8gUmV0dXJuIHRoZSBleHBvcnRzIG9mIHRoZSBtb2R1bGVcbiBcdFx0cmV0dXJuIG1vZHVsZS5leHBvcnRzO1xuIFx0fVxuXG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlcyBvYmplY3QgKF9fd2VicGFja19tb2R1bGVzX18pXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm0gPSBtb2R1bGVzO1xuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZSBjYWNoZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5jID0gaW5zdGFsbGVkTW9kdWxlcztcblxuIFx0Ly8gZGVmaW5lIGdldHRlciBmdW5jdGlvbiBmb3IgaGFybW9ueSBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQgPSBmdW5jdGlvbihleHBvcnRzLCBuYW1lLCBnZXR0ZXIpIHtcbiBcdFx0aWYoIV9fd2VicGFja19yZXF1aXJlX18ubyhleHBvcnRzLCBuYW1lKSkge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBuYW1lLCB7IGVudW1lcmFibGU6IHRydWUsIGdldDogZ2V0dGVyIH0pO1xuIFx0XHR9XG4gXHR9O1xuXG4gXHQvLyBkZWZpbmUgX19lc01vZHVsZSBvbiBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnIgPSBmdW5jdGlvbihleHBvcnRzKSB7XG4gXHRcdGlmKHR5cGVvZiBTeW1ib2wgIT09ICd1bmRlZmluZWQnICYmIFN5bWJvbC50b1N0cmluZ1RhZykge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBTeW1ib2wudG9TdHJpbmdUYWcsIHsgdmFsdWU6ICdNb2R1bGUnIH0pO1xuIFx0XHR9XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCAnX19lc01vZHVsZScsIHsgdmFsdWU6IHRydWUgfSk7XG4gXHR9O1xuXG4gXHQvLyBjcmVhdGUgYSBmYWtlIG5hbWVzcGFjZSBvYmplY3RcbiBcdC8vIG1vZGUgJiAxOiB2YWx1ZSBpcyBhIG1vZHVsZSBpZCwgcmVxdWlyZSBpdFxuIFx0Ly8gbW9kZSAmIDI6IG1lcmdlIGFsbCBwcm9wZXJ0aWVzIG9mIHZhbHVlIGludG8gdGhlIG5zXG4gXHQvLyBtb2RlICYgNDogcmV0dXJuIHZhbHVlIHdoZW4gYWxyZWFkeSBucyBvYmplY3RcbiBcdC8vIG1vZGUgJiA4fDE6IGJlaGF2ZSBsaWtlIHJlcXVpcmVcbiBcdF9fd2VicGFja19yZXF1aXJlX18udCA9IGZ1bmN0aW9uKHZhbHVlLCBtb2RlKSB7XG4gXHRcdGlmKG1vZGUgJiAxKSB2YWx1ZSA9IF9fd2VicGFja19yZXF1aXJlX18odmFsdWUpO1xuIFx0XHRpZihtb2RlICYgOCkgcmV0dXJuIHZhbHVlO1xuIFx0XHRpZigobW9kZSAmIDQpICYmIHR5cGVvZiB2YWx1ZSA9PT0gJ29iamVjdCcgJiYgdmFsdWUgJiYgdmFsdWUuX19lc01vZHVsZSkgcmV0dXJuIHZhbHVlO1xuIFx0XHR2YXIgbnMgPSBPYmplY3QuY3JlYXRlKG51bGwpO1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLnIobnMpO1xuIFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkobnMsICdkZWZhdWx0JywgeyBlbnVtZXJhYmxlOiB0cnVlLCB2YWx1ZTogdmFsdWUgfSk7XG4gXHRcdGlmKG1vZGUgJiAyICYmIHR5cGVvZiB2YWx1ZSAhPSAnc3RyaW5nJykgZm9yKHZhciBrZXkgaW4gdmFsdWUpIF9fd2VicGFja19yZXF1aXJlX18uZChucywga2V5LCBmdW5jdGlvbihrZXkpIHsgcmV0dXJuIHZhbHVlW2tleV07IH0uYmluZChudWxsLCBrZXkpKTtcbiBcdFx0cmV0dXJuIG5zO1xuIFx0fTtcblxuIFx0Ly8gZ2V0RGVmYXVsdEV4cG9ydCBmdW5jdGlvbiBmb3IgY29tcGF0aWJpbGl0eSB3aXRoIG5vbi1oYXJtb255IG1vZHVsZXNcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubiA9IGZ1bmN0aW9uKG1vZHVsZSkge1xuIFx0XHR2YXIgZ2V0dGVyID0gbW9kdWxlICYmIG1vZHVsZS5fX2VzTW9kdWxlID9cbiBcdFx0XHRmdW5jdGlvbiBnZXREZWZhdWx0KCkgeyByZXR1cm4gbW9kdWxlWydkZWZhdWx0J107IH0gOlxuIFx0XHRcdGZ1bmN0aW9uIGdldE1vZHVsZUV4cG9ydHMoKSB7IHJldHVybiBtb2R1bGU7IH07XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18uZChnZXR0ZXIsICdhJywgZ2V0dGVyKTtcbiBcdFx0cmV0dXJuIGdldHRlcjtcbiBcdH07XG5cbiBcdC8vIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbFxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5vID0gZnVuY3Rpb24ob2JqZWN0LCBwcm9wZXJ0eSkgeyByZXR1cm4gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsKG9iamVjdCwgcHJvcGVydHkpOyB9O1xuXG4gXHQvLyBfX3dlYnBhY2tfcHVibGljX3BhdGhfX1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5wID0gXCJcIjtcblxuXG4gXHQvLyBMb2FkIGVudHJ5IG1vZHVsZSBhbmQgcmV0dXJuIGV4cG9ydHNcbiBcdHJldHVybiBfX3dlYnBhY2tfcmVxdWlyZV9fKF9fd2VicGFja19yZXF1aXJlX18ucyA9IFwiLi9hc3NldHMvc2NyaXB0cy9tZWRpYS11cGxvYWQuanNcIik7XG4iLCJmdW5jdGlvbiB1cGxvYWRCdXR0b24oKSB7XG4gIGpRdWVyeSgnLndwX2V4cHJlc3MuYnRuLXVwbG9hZCcpLmNsaWNrKChlKSA9PiB7XG4gICAgY29uc3QgaWQgPSBqUXVlcnkoZS5jdXJyZW50VGFyZ2V0KS5hdHRyKCdkYXRhLWlkJyk7XG4gICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuXG4gICAgY29uc3QgZnJhbWUgPSB3cC5tZWRpYSh7XG4gICAgICB0aXRsZTogJ1NlbGVjdCBvciBVcGxvYWQgTWVkaWEgT2YgWW91ciBDaG9zZW4gUGVyc3Vhc2lvbicsXG4gICAgICBidXR0b246IHsgdGV4dDogJ1VzZSB0aGlzIG1lZGlhJyB9LFxuICAgICAgbXVsdGlwbGU6IGZhbHNlLFxuICAgIH0pO1xuXG4gICAgZnJhbWUub24oJ3NlbGVjdCcsICgpID0+IHtcbiAgICAgIGNvbnN0IGF0dGFjaG1lbnQgPSBmcmFtZS5zdGF0ZSgpLmdldCgnc2VsZWN0aW9uJykuZmlyc3QoKS50b0pTT04oKTtcbiAgICAgIGpRdWVyeShgIyR7aWR9LWN1c3RvbS1pbWctY29udGFpbmVyYClcbiAgICAgICAgLmFwcGVuZChgPGltZyBzcmM9XCIke2F0dGFjaG1lbnQudXJsfVwiIHdpZHRoPVwiMTUwXCIgLz5gKTtcbiAgICAgIGpRdWVyeShgIyR7aWR9LWN1c3RvbS1pbWctaWRgKS52YWwoYXR0YWNobWVudC5pZCk7XG5cbiAgICAgIGpRdWVyeShgIyR7aWR9LXVwbG9hZC1jdXN0b20taW1nYCkuYWRkQ2xhc3MoJ2hpZGRlbicpO1xuICAgICAgalF1ZXJ5KGAjJHtpZH0tZGVsZXRlLWN1c3RvbS1pbWdgKS5yZW1vdmVDbGFzcygnaGlkZGVuJyk7XG4gICAgfSk7XG5cbiAgICBmcmFtZS5vcGVuKCk7XG4gIH0pO1xufVxuXG5mdW5jdGlvbiByZW1vdmVCdXR0b24oKSB7XG4gIGpRdWVyeSgnLndwX2V4cHJlc3MuYnRuLXJlbW92ZScpLm9uKCdjbGljaycsIChlKSA9PiB7XG4gICAgY29uc3QgaWQgPSBqUXVlcnkoZS5jdXJyZW50VGFyZ2V0KS5hdHRyKCdkYXRhLWlkJyk7XG4gICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuXG4gICAgalF1ZXJ5KGAjJHtpZH0tY3VzdG9tLWltZy1jb250YWluZXJgKS5odG1sKCcnKTtcbiAgICBqUXVlcnkoYCMke2lkfS1jdXN0b20taW1nLWlkYCkudmFsKCcnKTtcblxuICAgIGpRdWVyeShgIyR7aWR9LXVwbG9hZC1jdXN0b20taW1nYCkucmVtb3ZlQ2xhc3MoJ2hpZGRlbicpO1xuICAgIGpRdWVyeShgIyR7aWR9LWRlbGV0ZS1jdXN0b20taW1nYCkuYWRkQ2xhc3MoJ2hpZGRlbicpO1xuICB9KTtcbn1cblxualF1ZXJ5KGRvY3VtZW50KS5yZWFkeSgoKSA9PiB7XG4gIHVwbG9hZEJ1dHRvbigpO1xuICByZW1vdmVCdXR0b24oKTtcbn0pO1xuIl0sInNvdXJjZVJvb3QiOiIifQ==