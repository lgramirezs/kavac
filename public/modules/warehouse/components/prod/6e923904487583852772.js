(window.webpackJsonp=window.webpackJsonp||[]).push([[22],{"KHd+":function(t,e,n){"use strict";function o(t,e,n,o,i,s,a,r){var c,l="function"==typeof t?t.options:t;if(e&&(l.render=e,l.staticRenderFns=n,l._compiled=!0),o&&(l.functional=!0),s&&(l._scopeId="data-v-"+s),a?(c=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),i&&i.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(a)},l._ssrRegister=c):i&&(c=r?function(){i.call(this,this.$root.$options.shadowRoot)}:i),c)if(l.functional){l._injectStyles=c;var d=l.render;l.render=function(t,e){return c.call(e),d(t,e)}}else{var f=l.beforeCreate;l.beforeCreate=f?[].concat(f,c):[c]}return{exports:t,options:l}}n.d(e,"a",function(){return o})},"QI++":function(t,e,n){"use strict";n.r(e);var o={data:function(){return{records:[],columns:["code","payroll_staff","motive","state","created_at","id"]}},created:function(){this.table_options.headings={code:"Código",payroll_staff:"Solicitante",motive:"Motivo",state:"Estado de la Solicitud",created_at:"Fecha de la Solicitud",id:"Acción"},this.table_options.sortable=["code","payroll_staff","motive","state","created_at"],this.table_options.filterable=["code","payroll_staff","motive","state","created_at"]},mounted:function(){this.initRecords(this.route_list,"")},methods:{reset:function(){}}},i=n("KHd+"),s=Object(i.a)(o,function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-client-table",{attrs:{columns:t.columns,data:t.records,options:t.table_options},scopedSlots:t._u([{key:"code",fn:function(e){return n("div",{staticClass:"text-center"},[n("span",[t._v("\n\t\t\t"+t._s(e.row.code)+"\n\t\t")])])}},{key:"payroll_staff",fn:function(e){return n("div",{},[n("span",[t._v("\n\t\t\t"+t._s(e.row.payroll_staff?e.row.payroll_staff.first_name+" "+e.row.payroll_staff.last_name:"N/A")+"\n\t\t")])])}},{key:"id",fn:function(e){return n("div",{staticClass:"text-center"},[n("div",{staticClass:"d-inline-flex"},[n("warehouse-request-info",{attrs:{route_list:"requests/info/"+e.row.id}}),t._v(" "),n("button",{staticClass:"btn btn-warning btn-xs btn-icon btn-action",attrs:{title:"Modificar registro","data-toggle":"tooltip",type:"button",disabled:"Pendiente"!=e.row.state},on:{click:function(n){return t.editForm(e.row.id)}}},[n("i",{staticClass:"fa fa-edit"})]),t._v(" "),n("button",{staticClass:"btn btn-danger btn-xs btn-icon btn-action",attrs:{title:"Eliminar registro","data-toggle":"tooltip",type:"button",disabled:"Pendiente"!=e.row.state},on:{click:function(n){return t.deleteRecord(e.index,"")}}},[n("i",{staticClass:"fa fa-trash-o"})])],1)])}}])})},[],!1,null,null,null);e.default=s.exports}}]);