(window.webpackJsonp=window.webpackJsonp||[]).push([[25],{"KHd+":function(t,n,e){"use strict";function o(t,n,e,o,r,s,a,i){var u,d="function"==typeof t?t.options:t;if(n&&(d.render=n,d.staticRenderFns=e,d._compiled=!0),o&&(d.functional=!0),s&&(d._scopeId="data-v-"+s),a?(u=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),r&&r.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(a)},d._ssrRegister=u):r&&(u=i?function(){r.call(this,this.$root.$options.shadowRoot)}:r),u)if(d.functional){d._injectStyles=u;var c=d.render;d.render=function(t,n){return u.call(n),c(t,n)}}else{var l=d.beforeCreate;d.beforeCreate=l?[].concat(l,u):[u]}return{exports:t,options:d}}e.d(n,"a",(function(){return o}))},Pv9M:function(t,n,e){"use strict";e.r(n);var o={props:{saveRoute:{type:String,required:!0}},methods:{saveEvent:function(){this.$parent.createRecord(this.saveRoute)}},mounted:function(){}},r=e("KHd+"),s=Object(r.a)(o,(function(){var t=this,n=t.$createElement,e=t._self._c||n;return e("div",[e("button",{staticClass:"btn btn-default btn-sm btn-round btn-modal-close",attrs:{type:"button","data-dismiss":"modal"}},[t._v("\n        Cerrar\n    ")]),t._v(" "),e("button",{staticClass:"btn btn-primary btn-sm btn-round btn-modal-save",attrs:{type:"button"},on:{click:function(n){return t.saveEvent()}}},[t._v("\n        Guardar\n    ")])])}),[],!1,null,null,null);n.default=s.exports}}]);