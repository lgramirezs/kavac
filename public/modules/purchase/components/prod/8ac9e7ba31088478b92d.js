(window.webpackJsonp=window.webpackJsonp||[]).push([[6],{"KHd+":function(t,e,n){"use strict";function s(t,e,n,s,o,r,i,a){var c,d="function"==typeof t?t.options:t;if(e&&(d.render=e,d.staticRenderFns=n,d._compiled=!0),s&&(d.functional=!0),r&&(d._scopeId="data-v-"+r),i?(c=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),o&&o.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(i)},d._ssrRegister=c):o&&(c=a?function(){o.call(this,this.$root.$options.shadowRoot)}:o),c)if(d.functional){d._injectStyles=c;var l=d.render;d.render=function(t,e){return c.call(e),l(t,e)}}else{var u=d.beforeCreate;d.beforeCreate=u?[].concat(u,c):[c]}return{exports:t,options:d}}n.d(e,"a",function(){return s})},n3PL:function(t,e,n){"use strict";n.r(e);var s={props:{record_list:{type:Array,default:function(){return[]}}},data:function(){return{records:[],columns:["init_date","end_date","purchase_type.name","purchase_process.name","id"]}},created:function(){this.table_options.headings={init_date:"FECHA DE INICIO",end_date:"FECHA DE CULMINACIÓN","purchase_type.name":"TIPO DE COMPRA","purchase_process.name":"PROCESO DE COMPRA",id:"ACCIÓN"},this.table_options.columnsClasses={init_date:"col-xs-2 text-center",end_date:"col-xs-2 text-center","purchase_type.name":"col-xs-4","purchase_process.name":"col-xs-3",id:"col-xs-1"}},mounted:function(){this.records=this.record_list}},o=n("KHd+"),r=Object(o.a)(s,function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("section",[n("v-client-table",{attrs:{columns:t.columns,data:t.records,options:t.table_options},scopedSlots:t._u([{key:"init_date",fn:function(e){return n("div",{staticClass:"text-center"},[t._v("\n            "+t._s(t.format_date(e.row.init_date))+"\n        ")])}},{key:"end_date",fn:function(e){return n("div",{staticClass:"text-center"},[t._v("\n            "+t._s(t.format_date(e.row.end_date))+"\n        ")])}},{key:"id",fn:function(e){return n("div",{staticClass:"text-center"},[n("div",{staticClass:"d-inline-flex"},[e.row.active?t._e():n("purchase-plan-show",{attrs:{id:e.row.id,route_show:"/purchase/purchase_plans/"+e.row.id}}),t._v(" "),e.row.active?n("button",{staticClass:"btn btn-warning btn-xs btn-icon btn-action",attrs:{title:"Modificar registro","data-toggle":"tooltip"},on:{click:function(n){return t.editForm(e.row.id)}}},[n("i",{staticClass:"fa fa-edit"})]):t._e(),t._v(" "),e.row.active?n("button",{staticClass:"btn btn-danger btn-xs btn-icon btn-action",attrs:{title:"Eliminar registro","data-toggle":"tooltip"},on:{click:function(n){return t.deleteRecord(e.index,"/purchase/purchase_plans")}}},[n("i",{staticClass:"fa fa-trash-o"})]):t._e()],1)])}}])}),t._v(" "),n("hr")],1)},[],!1,null,null,null);e.default=r.exports}}]);