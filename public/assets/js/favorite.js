!function(e){function webpackJsonpCallback(t){for(var n,o,a=t[0],i=t[1],u=t[2],p=0,l=[];p<a.length;p++)o=a[p],Object.prototype.hasOwnProperty.call(r,o)&&r[o]&&l.push(r[o][0]),r[o]=0;for(n in i)Object.prototype.hasOwnProperty.call(i,n)&&(e[n]=i[n]);for(s&&s(t);l.length;)l.shift()();return c.push.apply(c,u||[]),checkDeferredModules()}function checkDeferredModules(){for(var e,t=0;t<c.length;t++){for(var n=c[t],o=!0,a=1;a<n.length;a++){var s=n[a];0!==r[s]&&(o=!1)}o&&(c.splice(t--,1),e=__webpack_require__(__webpack_require__.s=n[0]))}return e}var t={},r={8:0},c=[];function __webpack_require__(r){if(t[r])return t[r].exports;var c=t[r]={i:r,l:!1,exports:{}};return e[r].call(c.exports,c,c.exports,__webpack_require__),c.l=!0,c.exports}__webpack_require__.m=e,__webpack_require__.c=t,__webpack_require__.d=function(e,t,r){__webpack_require__.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},__webpack_require__.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},__webpack_require__.t=function(e,t){if(1&t&&(e=__webpack_require__(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(__webpack_require__.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var c in e)__webpack_require__.d(r,c,function(t){return e[t]}.bind(null,c));return r},__webpack_require__.n=function(e){var t=e&&e.__esModule?function getDefault(){return e.default}:function getModuleExports(){return e};return __webpack_require__.d(t,"a",t),t},__webpack_require__.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},__webpack_require__.p="/assets/";var n=window.webpackJsonp=window.webpackJsonp||[],o=n.push.bind(n);n.push=webpackJsonpCallback,n=n.slice();for(var a=0;a<n.length;a++)webpackJsonpCallback(n[a]);var s=o;c.push([81,0,1]),checkDeferredModules()}({26:function(e,t,r){"use strict";r.d(t,"a",(function(){return refreshToken}));var c=r(0),n=r.n(c),o=r(1),a=r.n(o);function refreshToken(){return _refreshToken.apply(this,arguments)}function _refreshToken(){return(_refreshToken=a()(n.a.mark((function _callee(){var e,t,r,c,o;return n.a.wrap((function _callee$(n){for(;;)switch(n.prev=n.next){case 0:if(e="jwt",(t=JSON.parse(localStorage.getItem(e)))&&t._links){n.next=4;break}throw"Token problem";case 4:return r=t._links.refresher.href+"?token="+t.refreshToken,c=null,n.prev=6,n.next=9,fetch(r,{method:"POST"});case 9:if((o=n.sent).ok){n.next=12;break}throw"Bad response: ".concat(o.status,". (refresh attempt)");case 12:return n.next=14,o.json();case 14:c=n.sent,n.next=21;break;case 17:throw n.prev=17,n.t0=n.catch(6),console.error("Failed to refresh tokens"),n.t0;case 21:null!==c&&localStorage.setItem(e,JSON.stringify(c));case 22:case"end":return n.stop()}}),_callee,null,[[6,17]])})))).apply(this,arguments)}},27:function(e,t,r){"use strict";var render=function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"product favorite",class:{discount:null!=e.product.discount&&0!=e.product.discount}},[null!=e.product.discount&&0!=e.product.discount?r("div",{staticClass:"discount__percent"},[e._v(e._s(e.product.discount)+" %")]):e._e(),e.favorite?r("div",{staticClass:"favorite__cross icon-menu-cancel",on:{click:function(t){return e.removeItem(e.product.id)}}}):e._e(),r("div",{staticClass:"product__block"},[e.product.picture?r("img",{staticClass:"product__img",attrs:{src:e.product.picture}}):r("img",{staticClass:"product__img",attrs:{src:"/assets/img/default.png"}})]),r("div",{staticClass:"product__content"},[r("div",{staticClass:"product__content_title",on:{click:e.getProduct}},[r("div",{staticClass:"product__content_title_name"},[e._v(e._s(e.product.title))]),r("div",{staticClass:"product__content_title_price"},[r("div",{staticClass:"main__price"},[e._v("₽ "+e._s(e.product.price))]),null!=e.product.discount&&0!=e.product.discount?r("div",{staticClass:"discount__price"},[e._v("₽ "+e._s(e.product.discount_price))]):e._e()])]),r("figure",{staticClass:"icon-add",on:{click:e.addItemToCart}})])])};render._withStripped=!0;var c=r(4),n=r.n(c),o=r(6);function ownKeys(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var c=Object.getOwnPropertySymbols(e);t&&(c=c.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,c)}return r}function _objectSpread(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?ownKeys(Object(r),!0).forEach((function(t){n()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):ownKeys(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var a={props:{product:Object,favorite:{type:Boolean,default:!1}},methods:_objectSpread(_objectSpread(_objectSpread({},Object(o.b)("cart",["addToCart"])),Object(o.b)("favorites",["removeItem"])),{},{addItemToCart:function addItemToCart(){var e={id:this.product.id,count:this.product.count};this.addToCart(e)},getProduct:function getProduct(){window.location.href=this.$props.product.pageRef},addCart:function addCart(){this.product.count<100&&(this.product.count++,this.product.total=this.product.count*this.product.price)},minCart:function minCart(){this.product.count<=1||(this.product.count--,this.product.total-=this.product.price)}}),computed:{itemImage:function itemImage(){return this.product.picture}}},s=r(7),i=Object(s.a)(a,render,[],!1,null,null,null);i.options.__file="src/vue/catalog/components/catalog-item.vue";t.a=i.exports},81:function(e,t,r){"use strict";r.r(t);var c=r(0),n=r.n(c),o=r(1),a=r.n(o),s=r(12),i=r(8),u=r.n(i),p=r(9),l=r(11),Layoutvue_type_template_id_fcc4f578_lang_pug_render=function(){var e=this.$createElement,t=this._self._c||e;return t("section",{staticClass:"liked"},[t("div",{staticClass:"wrapper"},[t("p",{staticClass:"title__page"},[this._v("Отложенные товары")]),this.loaded?t("div",{staticClass:"liked__wrap"},this._l(this.favorites,(function(e){return t("FavItem",{attrs:{product:e,favorite:!0}})})),1):this._e()])])};Layoutvue_type_template_id_fcc4f578_lang_pug_render._withStripped=!0;var _=r(4),d=r.n(_),f=r(27),b=r(6);function ownKeys(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var c=Object.getOwnPropertySymbols(e);t&&(c=c.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,c)}return r}function _objectSpread(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?ownKeys(Object(r),!0).forEach((function(t){d()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):ownKeys(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var v={data:function data(){return{loaded:!1}},components:{FavItem:f.a},computed:_objectSpread({},Object(b.c)("favorites",["favorites"])),methods:_objectSpread({},Object(b.b)("favorites",["fetchItems"])),created:function created(){var e=this;return a()(n.a.mark((function _callee(){return n.a.wrap((function _callee$(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,e.fetchItems();case 2:e.loaded=!0;case 3:case"end":return t.stop()}}),_callee)})))()}},h=r(7),w=Object(h.a)(v,Layoutvue_type_template_id_fcc4f578_lang_pug_render,[],!1,null,null,null);w.options.__file="src/vue/favorites/Layout.vue";var m=w.exports,O=function _default(e){u()(this,_default),new p.a({el:e,store:l.a,render:function render(e){return e(m)}})},k=r(26);document.addEventListener("DOMContentLoaded",a()(n.a.mark((function _callee(){return n.a.wrap((function _callee$(e){for(;;)switch(e.prev=e.next){case 0:return Object(s.a)(),e.prev=1,e.next=4,Object(k.a)();case 4:new O("#favorites"),e.next=13;break;case 7:return e.prev=7,e.t0=e.catch(1),console.log(e.t0.message),localStorage.removeItem("jwt"),window.modalLogin.toggle(),e.abrupt("return");case 13:case"end":return e.stop()}}),_callee,null,[[1,7]])}))))}});