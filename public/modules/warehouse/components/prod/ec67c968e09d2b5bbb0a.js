(window.webpackJsonp=window.webpackJsonp||[]).push([[8],{"KHd+":function(t,e,n){"use strict";function i(t,e,n,i,o,s,r,a){var d,c="function"==typeof t?t.options:t;if(e&&(c.render=e,c.staticRenderFns=n,c._compiled=!0),i&&(c.functional=!0),s&&(c._scopeId="data-v-"+s),r?(d=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),o&&o.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(r)},c._ssrRegister=d):o&&(d=a?function(){o.call(this,this.$root.$options.shadowRoot)}:o),d)if(c.functional){c._injectStyles=d;var u=c.render;c.render=function(t,e){return d.call(e),u(t,e)}}else{var l=c.beforeCreate;c.beforeCreate=l?[].concat(l,d):[d]}return{exports:t,options:c}}n.d(e,"a",function(){return i})},VaWS:function(t,e,n){"use strict";n.r(e);var i={data:function(){return{records:[],columns:["code","description","warehouse_initial","warehouse_end","state","id"]}},created:function(){this.table_options.headings={code:"Código",description:"Descripción",warehouse_initial:"Origen",warehouse_end:"Destino",state:"Estado de la solicitud",id:"Acción"},this.table_options.sortable=["code","description","warehouse_initial","warehouse_end","state"],this.table_options.filterable=["code","description","warehouse_initial","warehouse_end","state"]},mounted:function(){this.initRecords(this.route_list,"")},methods:{reset:function(){}}},o=n("KHd+"),s=Object(o.a)(i,function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-client-table",{attrs:{columns:t.columns,data:t.records,options:t.table_options},scopedSlots:t._u([{key:"code",fn:function(e){return n("div",{staticClass:"text-center"},[n("span",[t._v("\n                "+t._s(e.row.code)+"\n            ")])])}},{key:"warehouse_initial",fn:function(e){return n("div",{},[n("span",[t._v("\n\t\t\t\t"+t._s(e.row.warehouse_institution_warehouse_initial?e.row.warehouse_institution_warehouse_initial.warehouse.name:"N/A")+"\n\t\t\t")])])}},{key:"warehouse_end",fn:function(e){return n("div",{},[n("span",[t._v("\n\t\t\t\t"+t._s(e.row.warehouse_institution_warehouse_end?e.row.warehouse_institution_warehouse_end.warehouse.name:"N/A")+"\n\t\t\t")])])}},{key:"state",fn:function(e){return n("div",{},[n("span",[t._v("\n\t\t\t\t"+t._s(e.row.state?e.row.state:"N/A")+"\n\t\t\t")])])}},{key:"id",fn:function(e){return n("div",{staticClass:"text-center"},[n("div",{staticClass:"d-inline-flex"},[n("warehouse-movement-info",{attrs:{route_list:"movements/info/"+e.row.id}}),t._v(" "),n("button",{staticClass:"btn btn-warning btn-xs btn-icon btn-action",attrs:{title:"Modificar registro","data-toggle":"tooltip",type:"button",disabled:"Pendiente"!=e.row.state},on:{click:function(n){return t.editForm(e.row.id)}}},[n("i",{staticClass:"fa fa-edit"})]),t._v(" "),n("button",{staticClass:"btn btn-danger btn-xs btn-icon btn-action",attrs:{title:"Eliminar registro","data-toggle":"tooltip",type:"button",disabled:"Pendiente"!=e.row.state},on:{click:function(n){return t.deleteRecord(e.index,"")}}},[n("i",{staticClass:"fa fa-trash-o"})])],1)])}}])})},[],!1,null,null,null);e.default=s.exports}}]);