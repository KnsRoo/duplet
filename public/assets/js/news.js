!function(e){function webpackJsonpCallback(t){for(var n,o,i=t[0],c=t[1],u=t[2],_=0,l=[];_<i.length;_++)o=i[_],Object.prototype.hasOwnProperty.call(r,o)&&r[o]&&l.push(r[o][0]),r[o]=0;for(n in c)Object.prototype.hasOwnProperty.call(c,n)&&(e[n]=c[n]);for(a&&a(t);l.length;)l.shift()();return s.push.apply(s,u||[]),checkDeferredModules()}function checkDeferredModules(){for(var e,t=0;t<s.length;t++){for(var n=s[t],o=!0,i=1;i<n.length;i++){var a=n[i];0!==r[a]&&(o=!1)}o&&(s.splice(t--,1),e=__webpack_require__(__webpack_require__.s=n[0]))}return e}var t={},r={8:0},s=[];function __webpack_require__(r){if(t[r])return t[r].exports;var s=t[r]={i:r,l:!1,exports:{}};return e[r].call(s.exports,s,s.exports,__webpack_require__),s.l=!0,s.exports}__webpack_require__.m=e,__webpack_require__.c=t,__webpack_require__.d=function(e,t,r){__webpack_require__.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},__webpack_require__.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},__webpack_require__.t=function(e,t){if(1&t&&(e=__webpack_require__(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(__webpack_require__.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var s in e)__webpack_require__.d(r,s,function(t){return e[t]}.bind(null,s));return r},__webpack_require__.n=function(e){var t=e&&e.__esModule?function getDefault(){return e.default}:function getModuleExports(){return e};return __webpack_require__.d(t,"a",t),t},__webpack_require__.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},__webpack_require__.p="/assets/";var n=window.webpackJsonp=window.webpackJsonp||[],o=n.push.bind(n);n.push=webpackJsonpCallback,n=n.slice();for(var i=0;i<n.length;i++)webpackJsonpCallback(n[i]);var a=o;s.push([27,0]),checkDeferredModules()}([,,,function(e,t,r){"use strict";function headerSearch(){var e=document.querySelector(".menu__btn"),t=document.querySelector(".header__popup"),r=document.querySelector("body");e.onclick=function(){e.classList.toggle("menu__btn_active"),t.classList.toggle("header__popup_show"),r.classList.toggle("lock__scroll")};var s=document.querySelector(".search__btn"),n=document.querySelector(".cancel__btn"),o=document.querySelector(".search__box"),i=document.querySelector(".input__search"),a=document.querySelector(".phone"),c=document.querySelector(".profile"),u=document.querySelector(".nav");s.onclick=function(){o.classList.add("active"),i.classList.add("input__active"),s.classList.add("search__btn_active"),n.classList.add("cancel__btn_active"),a.classList.add("phone__hidden"),c.classList.add("profile__hidden"),u.classList.add("nav__hidden")},n.onclick=function(){o.classList.remove("active"),i.classList.remove("input__active"),s.classList.remove("search__btn_active"),n.classList.remove("cancel__btn_active"),a.classList.remove("phone__hidden"),c.classList.remove("profile__hidden"),u.classList.remove("nav__hidden")};var _=document.querySelector(".search__btn_mobile"),l=document.querySelector(".cancel__btn_mobile"),d=document.querySelector(".search__box_mobile"),p=document.querySelector(".input__search_mobile"),h=document.querySelector(".block__first"),f=document.querySelector(".mobile__block"),m=document.querySelector(".block__second");_.onclick=function(){d.classList.add("active"),p.classList.add("input__active"),_.classList.add("search__btn_active"),l.classList.add("cancel__btn_active"),h.classList.add("block__first_hidden"),f.classList.add("mobile__block_active"),m.classList.add("block__second_active")},l.onclick=function(){d.classList.remove("active"),p.classList.remove("input__active"),_.classList.remove("search__btn_active"),l.classList.remove("cancel__btn_active"),h.classList.remove("block__first_hidden"),f.classList.remove("mobile__block_active"),m.classList.remove("block__second_active")}}r.d(t,"a",(function(){return headerSearch}))},,,,,,,,,function(e,t,r){var s=r(19),n=r(20),o=r(21),i=r(23);e.exports=function _slicedToArray(e,t){return s(e)||n(e,t)||o(e,t)||i()}},,,,,,,function(e,t){e.exports=function _arrayWithHoles(e){if(Array.isArray(e))return e}},function(e,t){e.exports=function _iterableToArrayLimit(e,t){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(e)){var r=[],s=!0,n=!1,o=void 0;try{for(var i,a=e[Symbol.iterator]();!(s=(i=a.next()).done)&&(r.push(i.value),!t||r.length!==t);s=!0);}catch(e){n=!0,o=e}finally{try{s||null==a.return||a.return()}finally{if(n)throw o}}return r}}},function(e,t,r){var s=r(22);e.exports=function _unsupportedIterableToArray(e,t){if(e){if("string"==typeof e)return s(e,t);var r=Object.prototype.toString.call(e).slice(8,-1);return"Object"===r&&e.constructor&&(r=e.constructor.name),"Map"===r||"Set"===r?Array.from(e):"Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r)?s(e,t):void 0}}},function(e,t){e.exports=function _arrayLikeToArray(e,t){(null==t||t>e.length)&&(t=e.length);for(var r=0,s=new Array(t);r<t;r++)s[r]=e[r];return s}},function(e,t){e.exports=function _nonIterableRest(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}},,,,function(e,t,r){"use strict";r.r(t);var s=r(3),n=r(11),o=r.n(n),i=r(8),layoutvue_type_template_id_f7b294d8_lang_pug_render=function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("section",{staticClass:"news"},[e.loaded?r("div",{staticClass:"wrapper"},[r("div",{staticClass:"news__title"},[e._v("Новости")]),r("div",{staticClass:"news__block"},[r("img",{staticClass:"news__block_img",attrs:{src:e.first.picture,alt:""}}),r("div",{staticClass:"news__block_instruction"},[r("div",{staticClass:"news__block_instruction_flex"},[r("div",{staticClass:"instruction__title"},[e._v(e._s(e.first.title))]),r("div",{staticClass:"instruction__text"},[e._v(e._s(e.first.announce))]),r("a",{staticClass:"instruction__link",attrs:{href:e.first.pageRef}},[e._v("Читать")])])])]),r("div",{staticClass:"news__cards"},e._l(e.news,(function(e){return r("newItem",{attrs:{newsItem:e}})})),1)]):e._e()])};layoutvue_type_template_id_f7b294d8_lang_pug_render._withStripped=!0;var a=r(7),c=r.n(a),u=r(9),_=r.n(u);
/*! MIT License © Sindre Sorhus */
const l={},d=["Headers","Request","Response","ReadableStream","fetch","AbortController","FormData"];for(const e of d)Object.defineProperty(l,e,{get(){const t=globalThis[e];return"function"==typeof t?t.bind(globalThis):t}});const isObject=e=>null!==e&&"object"==typeof e,p="function"==typeof l.AbortController,h="function"==typeof l.ReadableStream,f="function"==typeof l.FormData,mergeHeaders=(e,t)=>{const r=new l.Headers(e||{}),s=t instanceof l.Headers,n=new l.Headers(t||{});for(const[e,t]of n)s&&"undefined"===t||void 0===t?r.delete(e):r.set(e,t);return r},deepMerge=(...e)=>{let t={},r={};for(const s of e){if(Array.isArray(s))Array.isArray(t)||(t=[]),t=[...t,...s];else if(isObject(s)){for(let[e,r]of Object.entries(s))isObject(r)&&e in t&&(r=deepMerge(t[e],r)),t={...t,[e]:r};isObject(s.headers)&&(r=mergeHeaders(r,s.headers))}t.headers=r}return t},m=["get","post","put","patch","head","delete"],b={json:"application/json",text:"text/*",formData:"multipart/form-data",arrayBuffer:"*/*",blob:"*/*"},y=[413,429,503],w=Symbol("stop");class HTTPError extends Error{constructor(e,t,r){super(e.statusText||String(0===e.status||e.status?e.status:"Unknown response error")),this.name="HTTPError",this.response=e,this.request=t,this.options=r}}class TimeoutError extends Error{constructor(e){super("Request timed out"),this.name="TimeoutError",this.request=e}}const delay=e=>new Promise(t=>setTimeout(t,e)),normalizeRequestMethod=e=>m.includes(e)?e.toUpperCase():e,v={limit:2,methods:["get","put","head","delete","options","trace"],statusCodes:[408,413,429,500,502,503,504],afterStatusCodes:y},normalizeRetryOptions=(e={})=>{if("number"==typeof e)return{...v,limit:e};if(e.methods&&!Array.isArray(e.methods))throw new Error("retry.methods must be an array");if(e.statusCodes&&!Array.isArray(e.statusCodes))throw new Error("retry.statusCodes must be an array");return{...v,...e,afterStatusCodes:y}};class Ky{constructor(e,t={}){if(this._retryCount=0,this._input=e,this._options={credentials:this._input.credentials||"same-origin",...t,headers:mergeHeaders(this._input.headers,t.headers),hooks:deepMerge({beforeRequest:[],beforeRetry:[],afterResponse:[]},t.hooks),method:normalizeRequestMethod(t.method||this._input.method),prefixUrl:String(t.prefixUrl||""),retry:normalizeRetryOptions(t.retry),throwHttpErrors:!1!==t.throwHttpErrors,timeout:void 0===t.timeout?1e4:t.timeout,fetch:t.fetch||l.fetch},"string"!=typeof this._input&&!(this._input instanceof URL||this._input instanceof l.Request))throw new TypeError("`input` must be a string, URL, or Request");if(this._options.prefixUrl&&"string"==typeof this._input){if(this._input.startsWith("/"))throw new Error("`input` must not begin with a slash when using `prefixUrl`");this._options.prefixUrl.endsWith("/")||(this._options.prefixUrl+="/"),this._input=this._options.prefixUrl+this._input}if(p&&(this.abortController=new l.AbortController,this._options.signal&&this._options.signal.addEventListener("abort",()=>{this.abortController.abort()}),this._options.signal=this.abortController.signal),this.request=new l.Request(this._input,this._options),this._options.searchParams){const e="?"+new URLSearchParams(this._options.searchParams).toString(),t=this.request.url.replace(/(?:\?.*?)?(?=#|$)/,e);!(f&&this._options.body instanceof l.FormData||this._options.body instanceof URLSearchParams)||this._options.headers&&this._options.headers["content-type"]||this.request.headers.delete("content-type"),this.request=new l.Request(new l.Request(t,this.request),this._options)}void 0!==this._options.json&&(this._options.body=JSON.stringify(this._options.json),this.request.headers.set("content-type","application/json"),this.request=new l.Request(this.request,{body:this._options.body}));const fn=async()=>{if(this._options.timeout>2147483647)throw new RangeError("The `timeout` option cannot be greater than 2147483647");await delay(1);let e=await this._fetch();for(const t of this._options.hooks.afterResponse){const r=await t(this.request,this._options,this._decorateResponse(e.clone()));r instanceof l.Response&&(e=r)}if(this._decorateResponse(e),!e.ok&&this._options.throwHttpErrors)throw new HTTPError(e,this.request,this._options);if(this._options.onDownloadProgress){if("function"!=typeof this._options.onDownloadProgress)throw new TypeError("The `onDownloadProgress` option must be a function");if(!h)throw new Error("Streams are not supported in your environment. `ReadableStream` is missing.");return this._stream(e.clone(),this._options.onDownloadProgress)}return e},r=this._options.retry.methods.includes(this.request.method.toLowerCase())?this._retry(fn):fn();for(const[e,s]of Object.entries(b))r[e]=async()=>{this.request.headers.set("accept",this.request.headers.get("accept")||s);const n=(await r).clone();if("json"===e){if(204===n.status)return"";if(t.parseJson)return t.parseJson(await n.text())}return n[e]()};return r}_calculateRetryDelay(e){if(this._retryCount++,this._retryCount<this._options.retry.limit&&!(e instanceof TimeoutError)){if(e instanceof HTTPError){if(!this._options.retry.statusCodes.includes(e.response.status))return 0;const t=e.response.headers.get("Retry-After");if(t&&this._options.retry.afterStatusCodes.includes(e.response.status)){let e=Number(t);return Number.isNaN(e)?e=Date.parse(t)-Date.now():e*=1e3,void 0!==this._options.retry.maxRetryAfter&&e>this._options.retry.maxRetryAfter?0:e}if(413===e.response.status)return 0}return.3*2**(this._retryCount-1)*1e3}return 0}_decorateResponse(e){return this._options.parseJson&&(e.json=async()=>this._options.parseJson(await e.text())),e}async _retry(e){try{return await e()}catch(t){const r=Math.min(this._calculateRetryDelay(t),2147483647);if(0!==r&&this._retryCount>0){await delay(r);for(const e of this._options.hooks.beforeRetry){if(await e({request:this.request,options:this._options,error:t,retryCount:this._retryCount})===w)return}return this._retry(e)}if(this._options.throwHttpErrors)throw t}}async _fetch(){for(const e of this._options.hooks.beforeRequest){const t=await e(this.request,this._options);if(t instanceof Request){this.request=t;break}if(t instanceof Response)return t}return!1===this._options.timeout?this._options.fetch(this.request.clone()):(e=this.request.clone(),t=this.abortController,r=this._options,new Promise((s,n)=>{const o=setTimeout(()=>{t&&t.abort(),n(new TimeoutError(e))},r.timeout);r.fetch(e).then(s).catch(n).then(()=>{clearTimeout(o)})}));var e,t,r}_stream(e,t){const r=Number(e.headers.get("content-length"))||0;let s=0;return new l.Response(new l.ReadableStream({async start(n){const o=e.body.getReader();t&&t({percent:0,transferredBytes:0,totalBytes:r},new Uint8Array),await async function read(){const{done:e,value:i}=await o.read();if(e)n.close();else{if(t){s+=i.byteLength;t({percent:0===r?0:s/r,transferredBytes:s,totalBytes:r},i)}n.enqueue(i),await read()}}()}}))}}const validateAndMerge=(...e)=>{for(const t of e)if((!isObject(t)||Array.isArray(t))&&void 0!==t)throw new TypeError("The `options` argument must be an object");return deepMerge({},...e)},createInstance=e=>{const ky=(t,r)=>new Ky(t,validateAndMerge(e,r));for(const t of m)ky[t]=(r,s)=>new Ky(r,validateAndMerge(e,s,{method:t}));return ky.HTTPError=HTTPError,ky.TimeoutError=TimeoutError,ky.create=e=>createInstance(validateAndMerge(e)),ky.extend=t=>createInstance(validateAndMerge(e,t)),ky.stop=w,ky};var g=createInstance(),new_itemvue_type_template_id_56884bec_lang_pug_render=function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"news__card"},[r("img",{staticClass:"news__card_img",attrs:{src:e.newsItem.picture,alt:""}}),r("div",{staticClass:"news__card_title"},[e._v(e._s(e.newsItem.title))]),r("div",{staticClass:"news__card_read"},[r("a",{staticClass:"news__card_read_title",attrs:{href:e.newsItem.pageRef}},[e._v("Читать")]),r("div",{staticClass:"news__card_read_data"},[r("img",{staticClass:"news__card_read_data_svg",attrs:{src:"/assets/img/icons/data.svg",alt:""}}),r("div",{staticClass:"news__card_read_data_number"},[e._v(e._s(e.format(e.newsItem.creationDate)))])])])])};new_itemvue_type_template_id_56884bec_lang_pug_render._withStripped=!0;var q=r(12),k=r.n(q),x={data:function data(){return{}},methods:{format:function format(e){var t=e.split(" "),r=k()(t,2),s=r[0],n=(r[1],s.split("-")),o=k()(n,3),i=o[0],a=o[1],c=o[2];return"".concat(c,".").concat(a,".").concat(i)}},props:{newsItem:Object}},C=r(4),S=Object(C.a)(x,new_itemvue_type_template_id_56884bec_lang_pug_render,[],!1,null,null,null);S.options.__file="src/vue/news/new-item.vue";var L={data:function data(){return{loaded:!1,first:{},news:[],urls:{prev:null,next:null}}},components:{newItem:S.exports},methods:{fetchNews:function fetchNews(){var e=this;return _()(c.a.mark((function _callee(){var t;return c.a.wrap((function _callee$(r){for(;;)switch(r.prev=r.next){case 0:return r.next=2,g.get("/api/news/lines").json();case 2:t=r.sent,e.news=t._embedded.items,e.first=e.news.shift(),e.urls.next=t._links.next,e.loaded=!0;case 7:case"end":return r.stop()}}),_callee)})))()},next:function next(){var e=this;return _()(c.a.mark((function _callee2(){var t;return c.a.wrap((function _callee2$(r){for(;;)switch(r.prev=r.next){case 0:return r.next=2,g.get(e.urls.next).json();case 2:t=r.sent,e.news.push(t._embedded.items),e.urls.next=t._links.next;case 5:case"end":return r.stop()}}),_callee2)})))()}},created:function created(){var e=this;return _()(c.a.mark((function _callee3(){return c.a.wrap((function _callee3$(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,e.fetchNews();case 2:case"end":return t.stop()}}),_callee3)})))()}},R=Object(C.a)(L,layoutvue_type_template_id_f7b294d8_lang_pug_render,[],!1,null,null,null);R.options.__file="src/vue/news/layout.vue";var T=R.exports,E=function News(e){o()(this,News);document.querySelector(e);new i.a({el:e,render:function render(e){return e(T)}})};document.addEventListener("DOMContentLoaded",(function(){Object(s.a)(),new E("#news")}))}]);