!function(t){function webpackJsonpCallback(e){for(var n,o,i=e[0],u=e[1],c=e[2],_=0,l=[];_<i.length;_++)o=i[_],Object.prototype.hasOwnProperty.call(r,o)&&r[o]&&l.push(r[o][0]),r[o]=0;for(n in u)Object.prototype.hasOwnProperty.call(u,n)&&(t[n]=u[n]);for(a&&a(e);l.length;)l.shift()();return s.push.apply(s,c||[]),checkDeferredModules()}function checkDeferredModules(){for(var t,e=0;e<s.length;e++){for(var n=s[e],o=!0,i=1;i<n.length;i++){var a=n[i];0!==r[a]&&(o=!1)}o&&(s.splice(e--,1),t=__webpack_require__(__webpack_require__.s=n[0]))}return t}var e={},r={10:0},s=[];function __webpack_require__(r){if(e[r])return e[r].exports;var s=e[r]={i:r,l:!1,exports:{}};return t[r].call(s.exports,s,s.exports,__webpack_require__),s.l=!0,s.exports}__webpack_require__.m=t,__webpack_require__.c=e,__webpack_require__.d=function(t,e,r){__webpack_require__.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:r})},__webpack_require__.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},__webpack_require__.t=function(t,e){if(1&e&&(t=__webpack_require__(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var r=Object.create(null);if(__webpack_require__.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var s in t)__webpack_require__.d(r,s,function(e){return t[e]}.bind(null,s));return r},__webpack_require__.n=function(t){var e=t&&t.__esModule?function getDefault(){return t.default}:function getModuleExports(){return t};return __webpack_require__.d(e,"a",e),e},__webpack_require__.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},__webpack_require__.p="/assets/";var n=window.webpackJsonp=window.webpackJsonp||[],o=n.push.bind(n);n.push=webpackJsonpCallback,n=n.slice();for(var i=0;i<n.length;i++)webpackJsonpCallback(n[i]);var a=o;s.push([47,0,1]),checkDeferredModules()}({24:function(t,e,r){var s=r(40),n=r(41),o=r(25),i=r(42);t.exports=function _toConsumableArray(t){return s(t)||n(t)||o(t)||i()}},40:function(t,e,r){var s=r(26);t.exports=function _arrayWithoutHoles(t){if(Array.isArray(t))return s(t)}},41:function(t,e){t.exports=function _iterableToArray(t){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(t))return Array.from(t)}},42:function(t,e){t.exports=function _nonIterableSpread(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}},47:function(t,e,r){"use strict";r.r(e);var s=r(12),n=r(5),o=r.n(n),i=r(10),layoutvue_type_template_id_f7b294d8_lang_pug_render=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("section",{staticClass:"news"},[t.loaded?r("div",{staticClass:"wrapper"},[r("div",{staticClass:"news__title"},[t._v("Новости")]),t._l(t.newsChunked,(function(t){return r("newItem",{attrs:{newsItem:t}})})),r("div",{staticClass:"news__next"},[t.urls.next?r("a",{staticClass:"link",on:{click:t.next}},[t._v("Загрузить еще")]):t._e()])],2):t._e()])};layoutvue_type_template_id_f7b294d8_lang_pug_render._withStripped=!0;var a=r(0),u=r.n(a),c=r(1),_=r.n(c),l=r(24),p=r.n(l),h=r(9),new_itemvue_type_template_id_56884bec_lang_pug_render=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"news_wrapper"},[r("div",{staticClass:"news__block"},[r("div",{staticClass:"img__item"},[r("img",{staticClass:"img__news",attrs:{src:t.newsItem[0].picture,alt:""}})]),r("div",{staticClass:"news__block_instruction"},[r("div",{staticClass:"news__block_instruction_flex"},[r("div",{staticClass:"instruction__title"},[t._v(t._s(t.newsItem[0].title))]),r("div",{staticClass:"instruction__text"},[t._v(t._s(t.newsItem[0].announce))]),r("a",{staticClass:"news__link",attrs:{href:t.newsItem[0].pageRef}},[r("span",{staticClass:"news__link_title"},[t._v("Читать")])])])])]),r("div",{staticClass:"news__cards"},t._l(t.newsItem,(function(e,s){return 0!=s?r("div",{staticClass:"news__card"},[r("div",{staticClass:"img__item"},[r("img",{staticClass:"img__news",attrs:{src:e.picture,alt:""}})]),r("div",{staticClass:"news__card_title"},[t._v(t._s(e.title))]),r("div",{staticClass:"news__card_read"},[r("a",{staticClass:"news__link",attrs:{href:e.pageRef}},[r("span",{staticClass:"news__link_title"},[t._v("Читать")])]),r("span",{staticClass:"news__data"},[t._v(t._s(t.format(e.creationDate)))])])]):t._e()})),0)])};new_itemvue_type_template_id_56884bec_lang_pug_render._withStripped=!0;var f=r(7),d=r.n(f),w={data:function data(){return{}},methods:{format:function format(t){var e=t.split(" "),r=d()(e,2),s=r[0],n=(r[1],s.split("-")),o=d()(n,3),i=o[0],a=o[1],u=o[2];return"".concat(u,".").concat(a,".").concat(i)}},props:{newsItem:Array}},m=r(8),y=Object(m.a)(w,new_itemvue_type_template_id_56884bec_lang_pug_render,[],!1,null,null,null);y.options.__file="src/vue/news/new-item.vue";var b={data:function data(){return{loaded:!1,first:{},news:[],urls:{prev:null,next:null}}},components:{newItem:y.exports},computed:{newsChunked:function newsChunked(){var t=p()(this.news),e=[],r=parseInt(t.length/4)+1;return p()(Array(r)).forEach((function(r){e.push(t.splice(0,4))})),e}},methods:{fetchNews:function fetchNews(){var t=this;return _()(u.a.mark((function _callee(){var e;return u.a.wrap((function _callee$(r){for(;;)switch(r.prev=r.next){case 0:return r.next=2,h.a.get("/api/news/lines").json();case 2:e=r.sent,t.news=e._embedded.items,t.urls.next=e._links.next,t.loaded=!0;case 6:case"end":return r.stop()}}),_callee)})))()},next:function next(){var t=this;return _()(u.a.mark((function _callee2(){var e;return u.a.wrap((function _callee2$(r){for(;;)switch(r.prev=r.next){case 0:return r.next=2,h.a.get(t.urls.next.href).json();case 2:(e=r.sent)._embedded.items.forEach((function(e){t.news.push(e)})),t.urls.next=e._links.next;case 5:case"end":return r.stop()}}),_callee2)})))()}},created:function created(){var t=this;return _()(u.a.mark((function _callee3(){return u.a.wrap((function _callee3$(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,t.fetchNews();case 2:case"end":return e.stop()}}),_callee3)})))()}},v=Object(m.a)(b,layoutvue_type_template_id_f7b294d8_lang_pug_render,[],!1,null,null,null);v.options.__file="src/vue/news/layout.vue";var g=v.exports,C=function News(t){o()(this,News);document.querySelector(t);new i.a({el:t,render:function render(t){return t(g)}})};document.addEventListener("DOMContentLoaded",(function(){Object(s.a)(),new C("#news")}))},9:function(t,e,r){"use strict";
/*! MIT License © Sindre Sorhus */const s={},n=["Headers","Request","Response","ReadableStream","fetch","AbortController","FormData"];for(const t of n)Object.defineProperty(s,t,{get(){const e=globalThis[t];return"function"==typeof e?e.bind(globalThis):e}});const isObject=t=>null!==t&&"object"==typeof t,o="function"==typeof s.AbortController,i="function"==typeof s.ReadableStream,a="function"==typeof s.FormData,mergeHeaders=(t,e)=>{const r=new s.Headers(t||{}),n=e instanceof s.Headers,o=new s.Headers(e||{});for(const[t,e]of o)n&&"undefined"===e||void 0===e?r.delete(t):r.set(t,e);return r},deepMerge=(...t)=>{let e={},r={};for(const s of t){if(Array.isArray(s))Array.isArray(e)||(e=[]),e=[...e,...s];else if(isObject(s)){for(let[t,r]of Object.entries(s))isObject(r)&&t in e&&(r=deepMerge(e[t],r)),e={...e,[t]:r};isObject(s.headers)&&(r=mergeHeaders(r,s.headers))}e.headers=r}return e},u=["get","post","put","patch","head","delete"],c={json:"application/json",text:"text/*",formData:"multipart/form-data",arrayBuffer:"*/*",blob:"*/*"},_=[413,429,503],l=Symbol("stop");class HTTPError extends Error{constructor(t,e,r){super(t.statusText||String(0===t.status||t.status?t.status:"Unknown response error")),this.name="HTTPError",this.response=t,this.request=e,this.options=r}}class TimeoutError extends Error{constructor(t){super("Request timed out"),this.name="TimeoutError",this.request=t}}const delay=t=>new Promise(e=>setTimeout(e,t)),normalizeRequestMethod=t=>u.includes(t)?t.toUpperCase():t,p={limit:2,methods:["get","put","head","delete","options","trace"],statusCodes:[408,413,429,500,502,503,504],afterStatusCodes:_},normalizeRetryOptions=(t={})=>{if("number"==typeof t)return{...p,limit:t};if(t.methods&&!Array.isArray(t.methods))throw new Error("retry.methods must be an array");if(t.statusCodes&&!Array.isArray(t.statusCodes))throw new Error("retry.statusCodes must be an array");return{...p,...t,afterStatusCodes:_}};class Ky{constructor(t,e={}){if(this._retryCount=0,this._input=t,this._options={credentials:this._input.credentials||"same-origin",...e,headers:mergeHeaders(this._input.headers,e.headers),hooks:deepMerge({beforeRequest:[],beforeRetry:[],afterResponse:[]},e.hooks),method:normalizeRequestMethod(e.method||this._input.method),prefixUrl:String(e.prefixUrl||""),retry:normalizeRetryOptions(e.retry),throwHttpErrors:!1!==e.throwHttpErrors,timeout:void 0===e.timeout?1e4:e.timeout,fetch:e.fetch||s.fetch},"string"!=typeof this._input&&!(this._input instanceof URL||this._input instanceof s.Request))throw new TypeError("`input` must be a string, URL, or Request");if(this._options.prefixUrl&&"string"==typeof this._input){if(this._input.startsWith("/"))throw new Error("`input` must not begin with a slash when using `prefixUrl`");this._options.prefixUrl.endsWith("/")||(this._options.prefixUrl+="/"),this._input=this._options.prefixUrl+this._input}if(o&&(this.abortController=new s.AbortController,this._options.signal&&this._options.signal.addEventListener("abort",()=>{this.abortController.abort()}),this._options.signal=this.abortController.signal),this.request=new s.Request(this._input,this._options),this._options.searchParams){const t="?"+new URLSearchParams(this._options.searchParams).toString(),e=this.request.url.replace(/(?:\?.*?)?(?=#|$)/,t);!(a&&this._options.body instanceof s.FormData||this._options.body instanceof URLSearchParams)||this._options.headers&&this._options.headers["content-type"]||this.request.headers.delete("content-type"),this.request=new s.Request(new s.Request(e,this.request),this._options)}void 0!==this._options.json&&(this._options.body=JSON.stringify(this._options.json),this.request.headers.set("content-type","application/json"),this.request=new s.Request(this.request,{body:this._options.body}));const fn=async()=>{if(this._options.timeout>2147483647)throw new RangeError("The `timeout` option cannot be greater than 2147483647");await delay(1);let t=await this._fetch();for(const e of this._options.hooks.afterResponse){const r=await e(this.request,this._options,this._decorateResponse(t.clone()));r instanceof s.Response&&(t=r)}if(this._decorateResponse(t),!t.ok&&this._options.throwHttpErrors)throw new HTTPError(t,this.request,this._options);if(this._options.onDownloadProgress){if("function"!=typeof this._options.onDownloadProgress)throw new TypeError("The `onDownloadProgress` option must be a function");if(!i)throw new Error("Streams are not supported in your environment. `ReadableStream` is missing.");return this._stream(t.clone(),this._options.onDownloadProgress)}return t},r=this._options.retry.methods.includes(this.request.method.toLowerCase())?this._retry(fn):fn();for(const[t,s]of Object.entries(c))r[t]=async()=>{this.request.headers.set("accept",this.request.headers.get("accept")||s);const n=(await r).clone();if("json"===t){if(204===n.status)return"";if(e.parseJson)return e.parseJson(await n.text())}return n[t]()};return r}_calculateRetryDelay(t){if(this._retryCount++,this._retryCount<this._options.retry.limit&&!(t instanceof TimeoutError)){if(t instanceof HTTPError){if(!this._options.retry.statusCodes.includes(t.response.status))return 0;const e=t.response.headers.get("Retry-After");if(e&&this._options.retry.afterStatusCodes.includes(t.response.status)){let t=Number(e);return Number.isNaN(t)?t=Date.parse(e)-Date.now():t*=1e3,void 0!==this._options.retry.maxRetryAfter&&t>this._options.retry.maxRetryAfter?0:t}if(413===t.response.status)return 0}return.3*2**(this._retryCount-1)*1e3}return 0}_decorateResponse(t){return this._options.parseJson&&(t.json=async()=>this._options.parseJson(await t.text())),t}async _retry(t){try{return await t()}catch(e){const r=Math.min(this._calculateRetryDelay(e),2147483647);if(0!==r&&this._retryCount>0){await delay(r);for(const t of this._options.hooks.beforeRetry){if(await t({request:this.request,options:this._options,error:e,retryCount:this._retryCount})===l)return}return this._retry(t)}if(this._options.throwHttpErrors)throw e}}async _fetch(){for(const t of this._options.hooks.beforeRequest){const e=await t(this.request,this._options);if(e instanceof Request){this.request=e;break}if(e instanceof Response)return e}return!1===this._options.timeout?this._options.fetch(this.request.clone()):(t=this.request.clone(),e=this.abortController,r=this._options,new Promise((s,n)=>{const o=setTimeout(()=>{e&&e.abort(),n(new TimeoutError(t))},r.timeout);r.fetch(t).then(s).catch(n).then(()=>{clearTimeout(o)})}));var t,e,r}_stream(t,e){const r=Number(t.headers.get("content-length"))||0;let n=0;return new s.Response(new s.ReadableStream({async start(s){const o=t.body.getReader();e&&e({percent:0,transferredBytes:0,totalBytes:r},new Uint8Array),await async function read(){const{done:t,value:i}=await o.read();if(t)s.close();else{if(e){n+=i.byteLength;e({percent:0===r?0:n/r,transferredBytes:n,totalBytes:r},i)}s.enqueue(i),await read()}}()}}))}}const validateAndMerge=(...t)=>{for(const e of t)if((!isObject(e)||Array.isArray(e))&&void 0!==e)throw new TypeError("The `options` argument must be an object");return deepMerge({},...t)},createInstance=t=>{const ky=(e,r)=>new Ky(e,validateAndMerge(t,r));for(const e of u)ky[e]=(r,s)=>new Ky(r,validateAndMerge(t,s,{method:e}));return ky.HTTPError=HTTPError,ky.TimeoutError=TimeoutError,ky.create=t=>createInstance(validateAndMerge(t)),ky.extend=e=>createInstance(validateAndMerge(t,e)),ky.stop=l,ky},h=createInstance();e.a=h}});