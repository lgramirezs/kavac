(window.webpackJsonp=window.webpackJsonp||[]).push([[12],{"KHd+":function(t,e,n){"use strict";function o(t,e,n,o,s,i,r,a){var c,d="function"==typeof t?t.options:t;if(e&&(d.render=e,d.staticRenderFns=n,d._compiled=!0),o&&(d.functional=!0),i&&(d._scopeId="data-v-"+i),r?(c=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),s&&s.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(r)},d._ssrRegister=c):s&&(c=a?function(){s.call(this,this.$root.$options.shadowRoot)}:s),c)if(d.functional){d._injectStyles=c;var u=d.render;d.render=function(t,e){return c.call(e),u(t,e)}}else{var l=d.beforeCreate;d.beforeCreate=l?[].concat(l,c):[c]}return{exports:t,options:d}}n.d(e,"a",function(){return o})},dc5z:function(t,e,n){"use strict";n.r(e);var o={data:function(){return{records:[],columns:["code","description","warehouse","created_at","state","id"]}},created:function(){this.table_options.headings={code:"Código",description:"Descripción",warehouse:"Almacén",created_at:"Fecha de Ingreso",state:"Estado de la solicitud",id:"Acción"},this.table_options.sortable=["code","description","warehouse","created_at","state"],this.table_options.filterable=["code","description","warehouse","created_at","state"]},mounted:function(){this.initRecords(this.route_list,"")},methods:{reset:function(){}}},s=n("KHd+"),i=Object(s.a)(o,function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-client-table",{attrs:{columns:t.columns,data:t.records,options:t.table_options},scopedSlots:t._u([{key:"code",fn:function(e){return n("div",{staticClass:"text-center"},[n("span",[t._v("\n                "+t._s(e.row.code)+"\n            ")])])}},{key:"warehouse",fn:function(e){return n("div",{},[n("span",[t._v("\n\t\t\t\t"+t._s(e.row.warehouse_institution_warehouse_end?e.row.warehouse_institution_warehouse_end.warehouse.name:"N/A")+"\n\t\t\t")])])}},{key:"state",fn:function(e){return n("div",{},[n("span",[t._v("\n\t\t\t\t"+t._s(e.row.state?e.row.state:"N/A")+"\n\t\t\t")])])}},{key:"id",fn:function(e){return n("div",{staticClass:"text-center"},[n("div",{staticClass:"d-inline-flex"},[n("warehouse-reception-info",{attrs:{route_list:"/warehouse/receptions/info/"+e.row.id}}),t._v(" "),n("button",{staticClass:"btn btn-warning btn-xs btn-icon btn-action",attrs:{title:"Modificar registro","data-toggle":"tooltip",type:"button",disabled:"Pendiente"!=e.row.state},on:{click:function(n){return t.editForm(e.row.id)}}},[n("i",{staticClass:"fa fa-edit"})]),t._v(" "),n("button",{staticClass:"btn btn-danger btn-xs btn-icon btn-action",attrs:{title:"Eliminar registro","data-toggle":"tooltip",type:"button",disabled:"Pendiente"!=e.row.state},on:{click:function(n){return t.deleteRecord(e.index,"")}}},[n("i",{staticClass:"fa fa-trash-o"})])],1)])}}])})},[],!1,null,null,null);e.default=i.exports}}]);