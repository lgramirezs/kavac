(window.webpackJsonp=window.webpackJsonp||[]).push([[25],{kc0n:function(t,e,r){"use strict";r.r(e);var s={data:function(){return{records:[],errors:[],columns:["state","user.name","created_at","delivery_date","id"]}},created:function(){this.readRecords(this.route_list),this.table_options.headings={state:"Estado","user.name":"Solicitante",created_at:"Fecha de Emisión",delivery_date:"Fecha de Entrega",id:"Acción"},this.table_options.sortable=["state","created_at","delivery_date"],this.table_options.filterable=["state","created_at","delivery_date"]},methods:{acceptRequest:function(t){var e=this,r=this.records[t-1],s=this.records[t-1].id;axios.put("/"+this.route_update+"/request-approved/"+s,r).then((function(t){void 0!==t.data.redirect?location.href=t.data.redirect:(e.readRecords(url),e.reset(),e.showMessage("update"))})).catch((function(t){if(e.errors=[],void 0!==t.response)for(var r in t.response.data.errors)t.response.data.errors[r]&&e.errors.push(t.response.data.errors[r][0])}))},rejectedRequest:function(t){var e=this,r=this.records[t-1],s=this.records[t-1].id;axios.put("/"+this.route_update+"/request-rejected/"+s,r).then((function(t){void 0!==t.data.redirect?location.href=t.data.redirect:(e.readRecords(url),e.reset(),e.showMessage("update"))})).catch((function(t){if(e.errors=[],void 0!==t.response)for(var r in t.response.data.errors)t.response.data.errors[r]&&e.errors.push(t.response.data.errors[r][0])}))}}},a=r("KHd+"),o=Object(a.a)(s,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"col-md-12"},[r("hr"),t._v(" "),r("v-client-table",{attrs:{columns:t.columns,data:t.records,options:t.table_options},scopedSlots:t._u([{key:"id",fn:function(e){return r("div",{staticClass:"text-center"},[r("button",{staticClass:"btn btn-success btn-xs btn-icon btn-action",attrs:{title:"Aceptar Solicitud","data-toggle":"tooltip",type:"button"},on:{click:function(r){return t.acceptRequest(e.index)}}},[r("i",{staticClass:"fa fa-check"})]),t._v(" "),r("button",{staticClass:"btn btn-danger btn-xs btn-icon btn-action",attrs:{title:"Rechazar Solicitud","data-toggle":"tooltip",type:"button"},on:{click:function(r){return t.rejectedRequest(e.index)}}},[r("i",{staticClass:"fa fa-ban"})])])}}])})],1)}),[],!1,null,null,null);e.default=o.exports}}]);