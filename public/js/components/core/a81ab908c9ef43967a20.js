(window.webpackJsonp=window.webpackJsonp||[]).push([[4],{"KHd+":function(t,n,e){"use strict";function o(t,n,e,o,r,a,s,i){var c,l="function"==typeof t?t.options:t;if(n&&(l.render=n,l.staticRenderFns=e,l._compiled=!0),o&&(l.functional=!0),a&&(l._scopeId="data-v-"+a),s?(c=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),r&&r.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(s)},l._ssrRegister=c):r&&(c=i?function(){r.call(this,this.$root.$options.shadowRoot)}:r),c)if(l.functional){l._injectStyles=c;var d=l.render;l.render=function(t,n){return c.call(n),d(t,n)}}else{var u=l.beforeCreate;l.beforeCreate=u?[].concat(u,c):[c]}return{exports:t,options:l}}e.d(n,"a",(function(){return o}))},x2Sq:function(t,n,e){"use strict";e.r(n);var o={props:{display:{type:String,required:!1,default:"true"}},mounted:function(){$(window).scroll((function(){$(this).scrollTop()>50?$(".btn-display").fadeIn():$(".btn-display").fadeOut()}))}},r=e("KHd+"),a=Object(r.a)(o,(function(){var t=this,n=t.$createElement,e=t._self._c||n;return e("div",{class:{"btn-display":"true"===t.display}},[e("button",{staticClass:"btn btn-default btn-icon btn-round",attrs:{type:"button","data-toggle":"tooltip",title:"Borrar datos del formulario"},on:{click:t.$parent.reset}},[e("i",{staticClass:"fa fa-eraser"})]),t._v(" "),e("button",{staticClass:"btn btn-warning btn-icon btn-round",attrs:{type:"button","data-toggle":"tooltip",title:"Cancelar y regresar"},on:{click:function(n){return t.$parent.redirect_back(t.$parent.route_list)}}},[e("i",{staticClass:"fa fa-ban"})]),t._v(" "),e("button",{staticClass:"btn btn-success btn-icon btn-round",attrs:{type:"button","data-toggle":"tooltip",title:"Guardar registro"},on:{click:t.$parent.createRecord}},[e("i",{staticClass:"fa fa-save"})])])}),[],!1,null,null,null);n.default=a.exports}}]);