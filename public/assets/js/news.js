!function(e){function webpackJsonpCallback(t){for(var a,s,i=t[0],_=t[1],o=t[2],l=0,u=[];l<i.length;l++)s=i[l],Object.prototype.hasOwnProperty.call(n,s)&&n[s]&&u.push(n[s][0]),n[s]=0;for(a in _)Object.prototype.hasOwnProperty.call(_,a)&&(e[a]=_[a]);for(c&&c(t);u.length;)u.shift()();return r.push.apply(r,o||[]),checkDeferredModules()}function checkDeferredModules(){for(var e,t=0;t<r.length;t++){for(var a=r[t],s=!0,i=1;i<a.length;i++){var c=a[i];0!==n[c]&&(s=!1)}s&&(r.splice(t--,1),e=__webpack_require__(__webpack_require__.s=a[0]))}return e}var t={},n={12:0},r=[];function __webpack_require__(n){if(t[n])return t[n].exports;var r=t[n]={i:n,l:!1,exports:{}};return e[n].call(r.exports,r,r.exports,__webpack_require__),r.l=!0,r.exports}__webpack_require__.m=e,__webpack_require__.c=t,__webpack_require__.d=function(e,t,n){__webpack_require__.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},__webpack_require__.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},__webpack_require__.t=function(e,t){if(1&t&&(e=__webpack_require__(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(__webpack_require__.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)__webpack_require__.d(n,r,function(t){return e[t]}.bind(null,r));return n},__webpack_require__.n=function(e){var t=e&&e.__esModule?function getDefault(){return e.default}:function getModuleExports(){return e};return __webpack_require__.d(t,"a",t),t},__webpack_require__.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},__webpack_require__.p="/assets/";var a=window.webpackJsonp=window.webpackJsonp||[],s=a.push.bind(a);a.push=webpackJsonpCallback,a=a.slice();for(var i=0;i<a.length;i++)webpackJsonpCallback(a[i]);var c=s;r.push([82,0,1]),checkDeferredModules()}({33:function(e,t,n){var r=n(73),a=n(74),s=n(34),i=n(75);e.exports=function _toConsumableArray(e){return r(e)||a(e)||s(e)||i()}},73:function(e,t,n){var r=n(35);e.exports=function _arrayWithoutHoles(e){if(Array.isArray(e))return r(e)}},74:function(e,t){e.exports=function _iterableToArray(e){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(e))return Array.from(e)}},75:function(e,t){e.exports=function _nonIterableSpread(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}},82:function(e,t,n){"use strict";n.r(t);var r=n(13),a=n(8),s=n.n(a),i=n(9),layoutvue_type_template_id_f7b294d8_lang_pug_render=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("section",{staticClass:"news"},[e.loaded?n("div",{staticClass:"wrapper"},[n("div",{staticClass:"title__page"},[e._v("Новости")]),e._l(e.newsChunked,(function(e){return n("newItem",{attrs:{newsItem:e}})})),n("div",{staticClass:"news__next"},[e.urls.next?n("a",{staticClass:"link",on:{click:e.next}},[e._v("Загрузить еще")]):e._e()])],2):e._e()])};layoutvue_type_template_id_f7b294d8_lang_pug_render._withStripped=!0;var c=n(0),_=n.n(c),o=n(1),l=n.n(o),u=n(33),p=n.n(u),d=n(3),new_itemvue_type_template_id_56884bec_lang_pug_render=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"news_wrapper"},[n("div",{staticClass:"news__block"},[n("div",{staticClass:"img__item"},[n("img",{staticClass:"img__news",attrs:{src:e.newsItem[0].picture,alt:""}})]),n("div",{staticClass:"news__block_instruction"},[n("div",{staticClass:"news__title"},[e._v(e._s(e.newsItem[0].title))]),n("div",{staticClass:"news__text"},[e._v(e._s(e.newsItem[0].announce))]),n("div",{staticClass:"news__card_read"},[n("a",{staticClass:"btn",attrs:{href:e.newsItem[0].pageRef}},[n("span",{staticClass:"btn__name"},[e._v("Читать")])]),n("span",{staticClass:"news__data"},[n("figure",{staticClass:"icon-data"}),n("div",{staticClass:"news__data_title"},[e._v(e._s(e.format(e.item.creationDate)))])])])])]),n("div",{staticClass:"news__cards"},e._l(e.newsItem,(function(t,r){return 0!=r?n("div",{staticClass:"news__card"},[n("div",{staticClass:"img__item"},[n("img",{staticClass:"img__news",attrs:{src:t.picture,alt:""}})]),n("div",{staticClass:"news__title"},[e._v(e._s(t.title))]),n("div",{staticClass:"news__text"},[e._v(e._s(e.newsItem[0].announce))]),n("div",{staticClass:"news__card_read"},[n("a",{staticClass:"btn",attrs:{href:e.newsItem[0].pageRef}},[n("span",{staticClass:"btn__name"},[e._v("Читать")])]),n("span",{staticClass:"news__data"},[n("figure",{staticClass:"icon-data"}),n("div",{staticClass:"news__data_title"},[e._v(e._s(e.format(t.creationDate)))])])])]):e._e()})),0)])};new_itemvue_type_template_id_56884bec_lang_pug_render._withStripped=!0;var f=n(10),w=n.n(f),v={data:function data(){return{}},methods:{format:function format(e){var t=e.split(" "),n=w()(t,2),r=n[0],a=(n[1],r.split("-")),s=w()(a,3),i=s[0],c=s[1],_=s[2];return"".concat(_,".").concat(c,".").concat(i)}},props:{newsItem:Array}},b=n(6),m=Object(b.a)(v,new_itemvue_type_template_id_56884bec_lang_pug_render,[],!1,null,null,null);m.options.__file="src/vue/news/new-item.vue";var h={data:function data(){return{loaded:!1,first:{},news:[],urls:{prev:null,next:null}}},components:{newItem:m.exports},computed:{newsChunked:function newsChunked(){var e=p()(this.news),t=[],n=parseInt(e.length/4)+1;return p()(Array(n)).forEach((function(n){t.push(e.splice(0,4))})),t}},methods:{fetchNews:function fetchNews(){var e=this;return l()(_.a.mark((function _callee(){var t;return _.a.wrap((function _callee$(n){for(;;)switch(n.prev=n.next){case 0:return n.next=2,d.a.get("/api/news/lines").json();case 2:t=n.sent,e.news=t._embedded.items,e.urls.next=t._links.next,e.loaded=!0;case 6:case"end":return n.stop()}}),_callee)})))()},next:function next(){var e=this;return l()(_.a.mark((function _callee2(){var t;return _.a.wrap((function _callee2$(n){for(;;)switch(n.prev=n.next){case 0:return n.next=2,d.a.get(e.urls.next.href).json();case 2:(t=n.sent)._embedded.items.forEach((function(t){e.news.push(t)})),e.urls.next=t._links.next;case 5:case"end":return n.stop()}}),_callee2)})))()}},created:function created(){var e=this;return l()(_.a.mark((function _callee3(){return _.a.wrap((function _callee3$(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,e.fetchNews();case 2:case"end":return t.stop()}}),_callee3)})))()}},k=Object(b.a)(h,layoutvue_type_template_id_f7b294d8_lang_pug_render,[],!1,null,null,null);k.options.__file="src/vue/news/layout.vue";var C=k.exports,y=function News(e){s()(this,News);document.querySelector(e);new i.a({el:e,render:function render(e){return e(C)}})};document.addEventListener("DOMContentLoaded",(function(){Object(r.a)(),new y("#news")}))}});