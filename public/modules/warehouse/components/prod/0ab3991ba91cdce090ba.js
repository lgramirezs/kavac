(window.webpackJsonp=window.webpackJsonp||[]).push([[23],{"KHd+":function(e,t,r){"use strict";function a(e,t,r,a,o,n,s,i){var c,d="function"==typeof e?e.options:e;if(t&&(d.render=t,d.staticRenderFns=r,d._compiled=!0),a&&(d.functional=!0),n&&(d._scopeId="data-v-"+n),s?(c=function(e){(e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),o&&o.call(this,e),e&&e._registeredComponents&&e._registeredComponents.add(s)},d._ssrRegister=c):o&&(c=i?function(){o.call(this,this.$root.$options.shadowRoot)}:o),c)if(d.functional){d._injectStyles=c;var l=d.render;d.render=function(e,t){return c.call(t),l(e,t)}}else{var u=d.beforeCreate;d.beforeCreate=u?[].concat(u,c):[c]}return{exports:e,options:d}}r.d(t,"a",function(){return a})},d6KX:function(e,t,r){"use strict";r.r(t);var a={data:function(){return{records:[],columns:["code","requested_by","motive","state","created_at","id"]}},created:function(){this.table_options.headings={code:"Código",requested_by:"Solicitado por",motive:"Motivo",state:"Estado de la Solicitud",created_at:"Fecha de la Solicitud",id:"Acción"},this.table_options.sortable=["code","requested_by","motive","state","created_at"],this.table_options.filterable=["code","requested_by","motive","state","created_at"]},mounted:function(){this.initRecords(this.route_list,"")},methods:{reset:function(){},rejectedRequest:function(e){var t=this;bootbox.confirm({title:"Rechazar operación?",message:"<p>¿Seguro que desea rechazar esta operación?. Una vez rechazada la operación no se podrán realizar cambios en la misma.<p>",size:"medium",buttons:{cancel:{label:'<i class="fa fa-times"></i> Cancelar'},confirm:{label:'<i class="fa fa-check"></i> Confirmar'}},callback:function(r){if(r){var a=t.records[e-1],o=t.records[e-1].id;axios.put("/"+t.route_update+"/request-rejected/"+o,a).then(function(e){void 0!==e.data.redirect&&(location.href=e.data.redirect)}).catch(function(e){if(t.errors=[],void 0!==e.response)for(var r in e.response.data.errors)e.response.data.errors[r]&&t.errors.push(e.response.data.errors[r][0])})}}})},approvedRequest:function(e){var t=this;bootbox.confirm({title:"Aprobar operación?",message:"<p>¿Seguro que desea aprobar esta operación?. Una vez aprobada la operación no se podrán realizar cambios en la misma.<p>",size:"medium",buttons:{cancel:{label:'<i class="fa fa-times"></i> Cancelar'},confirm:{label:'<i class="fa fa-check"></i> Confirmar'}},callback:function(r){if(r){var a=t.records[e-1],o=t.records[e-1].id;axios.put("/"+t.route_update+"/request-approved/"+o,a).then(function(e){void 0!==e.data.redirect&&(location.href=e.data.redirect)}).catch(function(e){if(t.errors=[],void 0!==e.response)for(var r in e.response.data.errors)e.response.data.errors[r]&&t.errors.push(e.response.data.errors[r][0])})}}})}}},o=r("KHd+"),n=Object(o.a)(a,function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("v-client-table",{attrs:{columns:e.columns,data:e.records,options:e.table_options},scopedSlots:e._u([{key:"code",fn:function(t){return r("div",{staticClass:"text-center"},[r("span",[e._v("\n\t\t\t"+e._s(t.row.code)+"\n\t\t")])])}},{key:"requested_by",fn:function(t){return r("div",{},[r("span",[e._v("\n\t\t\t"+e._s(t.row.payroll_staff?t.row.payroll_staff.first_name+" "+t.row.payroll_staff.last_name:t.row.department?t.row.department.name:"")+"\n\t\t")])])}},{key:"id",fn:function(t){return r("div",{staticClass:"text-center"},[r("div",{staticClass:"d-inline-flex"},[r("warehouse-request-info",{attrs:{route_list:"requests/info/"+t.row.id}}),e._v(" "),0==t.row.delivered&&"Aprobado"==t.row.state?r("warehouse-request-pending",{attrs:{requestid:t.row.id}}):e._e(),e._v(" "),r("button",{staticClass:"btn btn-success btn-xs btn-icon btn-action",attrs:{title:"Aceptar Solicitud","data-toggle":"tooltip",type:"button",disabled:"Pendiente"!=t.row.state},on:{click:function(r){return e.approvedRequest(t.index)}}},[r("i",{staticClass:"fa fa-check"})]),e._v(" "),r("button",{staticClass:"btn btn-danger btn-xs btn-icon btn-action",attrs:{title:"Rechazar Solicitud","data-toggle":"tooltip",type:"button",disabled:"Pendiente"!=t.row.state},on:{click:function(r){return e.rejectedRequest(t.index)}}},[r("i",{staticClass:"fa fa-ban"})])],1)])}}])})},[],!1,null,null,null);t.default=n.exports}}]);