(window.webpackJsonp=window.webpackJsonp||[]).push([[10],{C7yQ:function(t,e,n){"use strict";n.r(e);var o={data:function(){return{records:[],columns:["code","year","specific_action","total_formulated","assigned","id"]}},created:function(){this.table_options.headings={code:"Código",year:"Año",specific_action:"Acc. Especifica",total_formulated:"Total Formulado",assigned:"Asignado",id:"Acción"},this.table_options.sortable=["code","year","specific_action"],this.table_options.filterable=["code","year","specific_action"],this.table_options.columnsClasses={code:"col-md-2",name:"col-md-1",specific_action:"col-md-4",total_formulated:"col-md-2 text-right",assigned:"col-md-1 text-center",id:"col-md-2"},this.table_options.orderBy={column:"code"}},mounted:function(){this.initRecords(this.route_list,"")},methods:{reset:function(){}}},i=n("KHd+"),s=Object(i.a)(o,function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-client-table",{attrs:{columns:t.columns,data:t.records,options:t.table_options},scopedSlots:t._u([{key:"id",fn:function(e){return n("div",{staticClass:"text-center"},[t.route_show?n("button",{staticClass:"btn btn-info btn-xs btn-icon btn-action btn-tooltip",attrs:{title:"Ver registro","data-toggle":"tooltip","data-placement":"bottom",type:"button"},on:{click:function(n){return t.showRecord(e.row.id)}}},[n("i",{staticClass:"fa fa-eye"})]):t._e(),t._v(" "),e.row.assigned?t._e():n("button",{staticClass:"btn btn-warning btn-xs btn-icon btn-action btn-tooltip",attrs:{title:"Modificar registro","data-toggle":"tooltip","data-placement":"bottom",type:"button"},on:{click:function(n){return t.editForm(e.row.id)}}},[n("i",{staticClass:"fa fa-edit"})]),t._v(" "),n("button",{staticClass:"btn btn-danger btn-xs btn-icon btn-action btn-tooltip",attrs:{title:"Eliminar registro","data-toggle":"tooltip","data-placement":"bottom",type:"button"},on:{click:function(n){return t.deleteRecord(e.index,"")}}},[n("i",{staticClass:"fa fa-trash-o"})])])}},{key:"year",fn:function(e){return n("div",{staticClass:"text-center"},[t._v("\n\t\t\t"+t._s(e.row.year)+"\n\t\t")])}},{key:"specific_action",fn:function(e){return n("div",{},[t._v("\n\t\t\t"+t._s(e.row.specific_action.code)+" - "+t._s(e.row.specific_action.name)+"\n\t\t")])}},{key:"assigned",fn:function(e){return n("div",{},[e.row.assigned?n("span",{staticClass:"text-success text-bold"},[t._v("SI")]):n("span",{staticClass:"text-danger text-bold"},[t._v("NO")])])}}])})},[],!1,null,null,null);e.default=s.exports},"KHd+":function(t,e,n){"use strict";function o(t,e,n,o,i,s,a,c){var r,d="function"==typeof t?t.options:t;if(e&&(d.render=e,d.staticRenderFns=n,d._compiled=!0),o&&(d.functional=!0),s&&(d._scopeId="data-v-"+s),a?(r=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),i&&i.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(a)},d._ssrRegister=r):i&&(r=c?function(){i.call(this,this.$root.$options.shadowRoot)}:i),r)if(d.functional){d._injectStyles=r;var l=d.render;d.render=function(t,e){return r.call(e),l(t,e)}}else{var u=d.beforeCreate;d.beforeCreate=u?[].concat(u,r):[r]}return{exports:t,options:d}}n.d(e,"a",function(){return o})}}]);