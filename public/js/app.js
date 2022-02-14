(self["webpackChunk"] = self["webpackChunk"] || []).push([["/js/app"],{

/***/ "./resources/js/MosquitoSystems/getProfile.js":
/*!****************************************************!*\
  !*** ./resources/js/MosquitoSystems/getProfile.js ***!
  \****************************************************/
/***/ (() => {



/***/ }),

/***/ "./resources/js/app.js":
/*!*****************************!*\
  !*** ./resources/js/app.js ***!
  \*****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var alpinejs__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! alpinejs */ "./node_modules/alpinejs/dist/module.esm.js");
__webpack_require__(/*! ./bootstrap */ "./resources/js/bootstrap.js");

window.$ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");

__webpack_require__(/*! ./categoryIsChosen */ "./resources/js/categoryIsChosen.js");

__webpack_require__(/*! ./MosquitoSystems/getProfile */ "./resources/js/MosquitoSystems/getProfile.js");


window.Alpine = alpinejs__WEBPACK_IMPORTED_MODULE_0__["default"];
alpinejs__WEBPACK_IMPORTED_MODULE_0__["default"].start();

/***/ }),

/***/ "./resources/js/bootstrap.js":
/*!***********************************!*\
  !*** ./resources/js/bootstrap.js ***!
  \***********************************/
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

window._ = __webpack_require__(/*! lodash */ "./node_modules/lodash/lodash.js");
/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */
// import Echo from 'laravel-echo';
// window.Pusher = require('pusher-js');
// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });

/***/ }),

/***/ "./resources/js/categoryIsChosen.js":
/*!******************************************!*\
  !*** ./resources/js/categoryIsChosen.js ***!
  \******************************************/
/***/ (() => {

$(document).ready(function () {
  $('#categories').change(function () {
    var id = $(this).find("option:selected").val();
    $.ajax({
      type: 'GET',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: '/ajax/get-items',
      data: {
        categoryId: id
      },
      success: function success(data) {
        console.log(data);
        $('#items').html(data);
      }
    });
  });
});

/***/ }),

/***/ "./resources/css/app.css":
/*!*******************************!*\
  !*** ./resources/css/app.css ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ __webpack_require__.O(0, ["css/app","/js/vendor"], () => (__webpack_exec__("./resources/js/app.js"), __webpack_exec__("./resources/css/app.css")));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ }
]);