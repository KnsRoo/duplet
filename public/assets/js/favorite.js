!function(e){function webpackJsonpCallback(t){for(var c,o,a=t[0],s=t[1],u=t[2],l=0,p=[];l<a.length;l++)o=a[l],Object.prototype.hasOwnProperty.call(r,o)&&r[o]&&p.push(r[o][0]),r[o]=0;for(c in s)Object.prototype.hasOwnProperty.call(s,c)&&(e[c]=s[c]);for(i&&i(t);p.length;)p.shift()();return n.push.apply(n,u||[]),checkDeferredModules()}function checkDeferredModules(){for(var e,t=0;t<n.length;t++){for(var c=n[t],o=!0,a=1;a<c.length;a++){var i=c[a];0!==r[i]&&(o=!1)}o&&(n.splice(t--,1),e=__webpack_require__(__webpack_require__.s=c[0]))}return e}var t={},r={8:0},n=[];function __webpack_require__(r){if(t[r])return t[r].exports;var n=t[r]={i:r,l:!1,exports:{}};return e[r].call(n.exports,n,n.exports,__webpack_require__),n.l=!0,n.exports}__webpack_require__.m=e,__webpack_require__.c=t,__webpack_require__.d=function(e,t,r){__webpack_require__.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},__webpack_require__.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},__webpack_require__.t=function(e,t){if(1&t&&(e=__webpack_require__(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(__webpack_require__.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var n in e)__webpack_require__.d(r,n,function(t){return e[t]}.bind(null,n));return r},__webpack_require__.n=function(e){var t=e&&e.__esModule?function getDefault(){return e.default}:function getModuleExports(){return e};return __webpack_require__.d(t,"a",t),t},__webpack_require__.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},__webpack_require__.p="/assets/";var c=window.webpackJsonp=window.webpackJsonp||[],o=c.push.bind(c);c.push=webpackJsonpCallback,c=c.slice();for(var a=0;a<c.length;a++)webpackJsonpCallback(c[a]);var i=o;n.push([107,0,1]),checkDeferredModules()}({107:function(e,t,r){"use strict";r.r(t);var n=r(0),c=r.n(n),o=r(1),a=r.n(o),i=r(16),s=r(11),u=r.n(s),l=r(12),p=r(14),Layoutvue_type_template_id_fcc4f578_lang_pug_render=function(){var e=this.$createElement,t=this._self._c||e;return t("section",{staticClass:"liked"},[t("div",{staticClass:"wrapper"},[t("p",{staticClass:"title__page"},[this._v("Отложенные товары")]),this.loaded?t("div",{staticClass:"liked__wrap"},this._l(this.favorites,(function(e){return t("FavItem",{attrs:{product:e,favorite:!0}})})),1):t("loader")],1)])};Layoutvue_type_template_id_fcc4f578_lang_pug_render._withStripped=!0;var d=r(4),_=r.n(d),f=r(23),v=r(21),b=r(8);function ownKeys(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function _objectSpread(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?ownKeys(Object(r),!0).forEach((function(t){_()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):ownKeys(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var h={data:function data(){return{loaded:!1}},components:{FavItem:f.a,loader:v.a},computed:_objectSpread({},Object(b.c)("favorites",["favorites"])),methods:_objectSpread({},Object(b.b)("favorites",["fetchItems"])),created:function created(){var e=this;return a()(c.a.mark((function _callee(){return c.a.wrap((function _callee$(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,e.fetchItems();case 2:e.loaded=!0;case 3:case"end":return t.stop()}}),_callee)})))()}},w=r(7),m=Object(w.a)(h,Layoutvue_type_template_id_fcc4f578_lang_pug_render,[],!1,null,null,null);m.options.__file="src/vue/favorites/Layout.vue";var O=m.exports,k=function _default(e){u()(this,_default),new l.default({el:e,store:p.a,render:function render(e){return e(O)}})},g=r(28);document.addEventListener("DOMContentLoaded",a()(c.a.mark((function _callee(){return c.a.wrap((function _callee$(e){for(;;)switch(e.prev=e.next){case 0:return Object(i.a)(),e.prev=1,e.next=4,Object(g.a)();case 4:new k("#favorites"),e.next=13;break;case 7:return e.prev=7,e.t0=e.catch(1),console.log(e.t0.message),localStorage.removeItem("jwt"),window.modalLogin.toggle(),e.abrupt("return");case 13:case"end":return e.stop()}}),_callee,null,[[1,7]])}))))},21:function(e,t,r){"use strict";var render=function(){var e=this.$createElement;this._self._c;return this._m(0)};render._withStripped=!0;var n={data:function data(){return{}}},c=r(7),o=Object(c.a)(n,render,[function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"loader-wrapper"},[t("div",{staticClass:"lds-default"},[t("div"),t("div"),t("div"),t("div"),t("div"),t("div"),t("div"),t("div"),t("div"),t("div"),t("div"),t("div")])])}],!1,null,null,null);o.options.__file="src/vue/loader/index.vue";t.a=o.exports},23:function(e,t,r){"use strict";var render=function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"product favorite",class:{discount:null!=e.product.discount&&0!=e.product.discount}},[null!=e.product.discount&&0!=e.product.discount?r("div",{staticClass:"discount__percent"},[e._v(e._s(e.product.discount)+" %")]):e._e(),e.favorite?r("div",{staticClass:"favorite__cross icon-menu-cancel",on:{click:function(t){return e.removeItem(e.product.id)}}}):e._e(),r("div",{staticClass:"product__block"},[e.product.picture?r("img",{staticClass:"product__img",attrs:{src:e.product.picture}}):r("img",{staticClass:"product__img",attrs:{src:"/assets/img/default.png"}})]),r("div",{staticClass:"product__content"},[r("div",{staticClass:"product__content_title",on:{click:e.getProduct}},[r("div",{staticClass:"product__content_title_name"},[e._v(e._s(e.product.title))]),r("div",{staticClass:"product__content_title_price"},[r("div",{staticClass:"main__price"},[e._v("₽ "+e._s(e.product.price))]),null!=e.product.discount&&0!=e.product.discount?r("div",{staticClass:"discount__price"},[e._v("₽ "+e._s(e.product.discount_price))]):e._e()])]),r("figure",{staticClass:"icon-add",on:{click:e.addItemToCart}})])])};render._withStripped=!0;var n=r(4),c=r.n(n),o=r(8);function ownKeys(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function _objectSpread(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?ownKeys(Object(r),!0).forEach((function(t){c()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):ownKeys(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var a={props:{product:Object,favorite:{type:Boolean,default:!1}},methods:_objectSpread(_objectSpread(_objectSpread({},Object(o.b)("cart",["addToCart"])),Object(o.b)("favorites",["removeItem"])),{},{addItemToCart:function addItemToCart(){var e={id:this.product.id,count:this.product.count};this.addToCart(e)},getProduct:function getProduct(){window.location.href=this.$props.product.pageRef},addCart:function addCart(){this.product.count<100&&(this.product.count++,this.product.total=this.product.count*this.product.price)},minCart:function minCart(){this.product.count<=1||(this.product.count--,this.product.total-=this.product.price)}}),computed:{itemImage:function itemImage(){return this.product.picture}}},i=r(7),s=Object(i.a)(a,render,[],!1,null,null,null);s.options.__file="src/vue/catalog/components/catalog-item.vue";t.a=s.exports},28:function(e,t,r){"use strict";r.d(t,"a",(function(){return refreshToken}));var n=r(0),c=r.n(n),o=r(1),a=r.n(o);function refreshToken(){return _refreshToken.apply(this,arguments)}function _refreshToken(){return(_refreshToken=a()(c.a.mark((function _callee(){var e,t,r,n,o;return c.a.wrap((function _callee$(c){for(;;)switch(c.prev=c.next){case 0:if(e="jwt",(t=JSON.parse(localStorage.getItem(e)))&&t._links){c.next=4;break}throw"Token problem";case 4:return r=t._links.refresher.href+"?token="+t.refreshToken,n=null,c.prev=6,c.next=9,fetch(r,{method:"POST"});case 9:if((o=c.sent).ok){c.next=12;break}throw"Bad response: ".concat(o.status,". (refresh attempt)");case 12:return c.next=14,o.json();case 14:n=c.sent,c.next=21;break;case 17:throw c.prev=17,c.t0=c.catch(6),console.error("Failed to refresh tokens"),c.t0;case 21:null!==n&&localStorage.setItem(e,JSON.stringify(n));case 22:case"end":return c.stop()}}),_callee,null,[[6,17]])})))).apply(this,arguments)}}});