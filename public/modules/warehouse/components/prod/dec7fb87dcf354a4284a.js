(window.webpackJsonp=window.webpackJsonp||[]).push([[12],{"4A43":function(e,t,n){"use strict";n.r(t);var r={data:function(){return{records:[],columns:["code","registered_by","description","state","created_at","id"]}},created:function(){this.table_options.headings={code:"Código",registered_by:"Registrado por",description:"Descripción",warehouse_initial:"Almacén",state:"Estado de la Solicitud",created_at:"Fecha de la Solicitud",id:"Acción"},this.table_options.sortable=["code","registered_by","description","warehouse_initial","state","created_at"],this.table_options.filterable=["code","registered_by","description","warehouse_initial","state","created_at"]},mounted:function(){this.initRecords(this.route_list,"")},methods:{reset:function(){},rejectedRequest:function(e){var t=this;bootbox.confirm({title:"Rechazar operación?",message:"<p>¿Seguro que desea rechazar esta operación?. Una vez rechazada la operación no se podrán realizar cambios en la misma.<p>",size:"medium",buttons:{cancel:{label:'<i class="fa fa-times"></i> Cancelar'},confirm:{label:'<i class="fa fa-check"></i> Confirmar'}},callback:function(n){if(n){var r=t.records[e-1],a=t.records[e-1].id;axios.put("/"+t.route_update+"/reception-rejected/"+a,r).then(function(e){void 0!==e.data.redirect&&(location.href=e.data.redirect)}).catch(function(e){if(t.errors=[],void 0!==e.response)for(var n in e.response.data.errors)e.response.data.errors[n]&&t.errors.push(e.response.data.errors[n][0])})}}})},approvedRequest:function(e){var t=this;bootbox.confirm({title:"Aprobar operación?",message:"<p>¿Seguro que desea aprobar esta operación?. Una vez aprobada la operación no se podrán realizar cambios en la misma.<p>",size:"medium",buttons:{cancel:{label:'<i class="fa fa-times"></i> Cancelar'},confirm:{label:'<i class="fa fa-check"></i> Confirmar'}},callback:function(n){if(n){var r=t.records[e-1],a=t.records[e-1].id;axios.put("/"+t.route_update+"/reception-approved/"+a,r).then(function(e){void 0!==e.data.redirect&&(location.href=e.data.redirect)}).catch(function(e){if(t.errors=[],void 0!==e.response)for(var n in e.response.data.errors)e.response.data.errors[n]&&t.errors.push(e.response.data.errors[n][0])})}}})}}},a=n("KHd+"),i=Object(a.a)(r,function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",[n("v-client-table",{attrs:{columns:e.columns,data:e.records,options:e.table_options},scopedSlots:e._u([{key:"code",fn:function(t){return n("div",{staticClass:"text-center"},[n("span",[e._v("\n                "+e._s(t.row.code)+"\n            ")])])}},{key:"registered_by",fn:function(t){return n("div",{},[n("span",[e._v("\n                "+e._s(t.row.user?t.row.user.name:"No definido")+"\n            ")])])}},{key:"warehouse_initial",fn:function(t){return n("div",{},[n("span",[e._v("\n                "+e._s(t.row.warehouse_institution_warehouse_initial?t.row.warehouse_institution_warehouse_initial.warehouse.name:"N/A")+"\n            ")])])}},{key:"state",fn:function(t){return n("div",{},[n("span",[e._v("\n                "+e._s(t.row.state?t.row.state:"N/A")+"\n            ")])])}},{key:"id",fn:function(t){return n("div",{staticClass:"text-center"},[n("div",{staticClass:"d-inline-flex"},[n("warehouse-reception-info",{attrs:{route_list:"receptions/info/"+t.row.id}}),e._v(" "),n("button",{staticClass:"btn btn-success btn-xs btn-icon btn-action",attrs:{title:"Aceptar Solicitud","data-toggle":"tooltip",type:"button",disabled:"Pendiente"!=t.row.state},on:{click:function(n){return e.approvedRequest(t.index)}}},[n("i",{staticClass:"fa fa-check"})]),e._v(" "),n("button",{staticClass:"btn btn-danger btn-xs btn-icon btn-action",attrs:{title:"Rechazar Solicitud","data-toggle":"tooltip",type:"button",disabled:"Pendiente"!=t.row.state},on:{click:function(n){return e.rejectedRequest(t.index)}}},[n("i",{staticClass:"fa fa-ban"})])],1)])}}])})],1)},[],!1,null,null,null);t.default=i.exports},"KHd+":function(e,t,n){"use strict";function r(e,t,n,r,a,i,o,s){var c,d="function"==typeof e?e.options:e;if(t&&(d.render=t,d.staticRenderFns=n,d._compiled=!0),r&&(d.functional=!0),i&&(d._scopeId="data-v-"+i),o?(c=function(e){(e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),a&&a.call(this,e),e&&e._registeredComponents&&e._registeredComponents.add(o)},d._ssrRegister=c):a&&(c=s?function(){a.call(this,this.$root.$options.shadowRoot)}:a),c)if(d.functional){d._injectStyles=c;var u=d.render;d.render=function(e,t){return c.call(t),u(e,t)}}else{var l=d.beforeCreate;d.beforeCreate=l?[].concat(l,c):[c]}return{exports:e,options:d}}n.d(t,"a",function(){return r})}}]);